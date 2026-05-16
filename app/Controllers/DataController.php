<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DataController extends Controller
{
    private const BATCH_SIZE = 500;
    private const DATA_DIR   = ROOT_PATH . '/data';

    public function import(): void
    {
        $this->view('data/import', [
            'pageTitle' => 'Importation des Données',
        ]);
    }

    public function process(): void
    {
        $dataDir = self::DATA_DIR;

        if (!is_dir($dataDir)) {
            $this->jsonResponse(['error' => "Le dossier '$dataDir' n'existe pas."], 400);
            return;
        }

        $db    = Database::getInstance();
        $pdo   = $db->getConnection();

        $tables = ['Bureau', 'Operation', 'Trafic', 'Carnet', 'Cnep'];
        foreach ($tables as $table) {
            $pdo->exec("ALTER TABLE `$table` DISABLE KEYS");
        }

        $stmts = $this->prepareStatements($pdo);

        $pdo->beginTransaction();
        $batchCount = 0;

        $handle = opendir($dataDir);
        if (!$handle) {
            $this->jsonResponse(['error' => 'Impossible de lire le dossier data.'], 500);
            return;
        }

        while (false !== ($file = readdir($handle))) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $filePath = "$dataDir/$file";
            if (!is_file($filePath)) {
                continue;
            }

            $spl = new \SplFileObject($filePath, 'r');
            $spl->setFlags(\SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);

            $CodeBureau = $NomBureau = $CodeOperation = $NomOperation = '';
            $DateOperation = $Bureau = $Wilaya = $CNDate = '';

            foreach ($spl as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }

                // Bureau block
                if (strpos($line, 'RECAPITULATIF') !== false) {
                    $parts      = explode(' ', $line);
                    $CodeBureau = trim($parts[4] ?? '');
                    $NomBureau  = trim(implode(' ', array_slice($parts, 5)));
                    $stmts['bureau']->execute([$CodeBureau, $NomBureau]);
                }

                // Operation block
                if (preg_match('/^\d{2} \d{2}/', $line)) {
                    $parts     = explode(' ', $line);
                    $codeParts = [trim($parts[0] ?? ''), trim($parts[1] ?? '')];
                    $CodeOperation = implode(' ', $codeParts);
                    $NomOperation  = trim(implode(' ', array_slice($parts, 2, 5)));
                    $stmts['operation']->execute([$CodeOperation, $NomOperation]);
                }

                // Carnet block
                if (strpos($line, 'BORDEREAU') !== false && strpos($line, 'NUMERO') !== false) {
                    $parts      = explode(' ', $line);
                    $CodeBureau = trim(($parts[6] ?? '') . ' ' . ($parts[7] ?? ''));
                    $NomBureau  = trim($parts[20] ?? '');
                }
                if (strpos($line, 'ARRETE LE PRESENT') !== false) {
                    $parts = explode(' ', $line);
                    $date  = trim($parts[14] ?? '');
                }
                if (strpos($line, 'TOTAL') !== false && strpos($line, 'UNITE') !== false) {
                    $parts         = explode(' ', $line);
                    $NombreCarnet  = trim($parts[4] ?? '0');
                    $stmts['carnet']->execute([$CodeBureau, $date ?? '', $NomBureau, $NombreCarnet]);
                }

                // Trafic block
                if (strpos($line, 'TRAFIC DU BUREAU') !== false) {
                    $parts      = explode(' ', $line);
                    $CodeBureau = trim(($parts[6] ?? '') . ' ' . ($parts[7] ?? ''));
                }
                if (strpos($line, 'DU MOIS DE') !== false) {
                    $parts         = explode(' ', $line);
                    $DateOperation = trim(implode(' ', array_slice($parts, -2)));
                }
                if (preg_match('/^\d{2}\s\d{2}\s/', $line) && strpos($line, 'RECAPITULATIF') === false) {
                    $parts           = preg_split('/\s+/', $line);
                    $NomOperation    = trim(implode(' ', array_slice($parts, 2, 5)));
                    $MontantReception = $this->cleanNum($parts[12] ?? '0');
                    $stmts['trafic_upsert']->execute([$CodeBureau, $NomOperation, $DateOperation, $MontantReception]);
                }

                // CNEP block
                if (strpos($line, 'CNEP 23') !== false || strpos($line, 'CNEP 24') !== false) {
                    $parts         = explode(' ', $line);
                    $CodeOperation = trim($parts[1] ?? '');
                    $NomOperation  = trim($parts[7] ?? '');
                } elseif (strpos($line, 'MOIS') !== false) {
                    $parts  = explode(' ', $line);
                    $CNDate = trim(($parts[12] ?? '') . ' ' . ($parts[21] ?? ''));
                } elseif (strpos($line, 'BUREAU') !== false && strpos($line, 'BUREAUX') === false) {
                    $parts  = explode('BUREAU', $line);
                    $Bureau = trim($parts[1] ?? '');
                } elseif (strpos($line, 'WILAYA') !== false) {
                    $parts  = explode('WILAYA', $line);
                    $Wilaya = trim($parts[1] ?? '');
                } elseif (strpos($line, '*  TOT   *') !== false) {
                    $parts   = explode('*', $line);
                    $RPVU    = trim($parts[2] ?? '');
                    $RIPV    = trim($parts[3] ?? '');
                    $Montant = $this->cleanNum($parts[5] ?? '');
                    $stmts['cnep']->execute([
                        $CodeOperation, $CNDate, $Bureau,
                        $NomOperation, $Wilaya, $RPVU, $RIPV, $Montant,
                    ]);
                }

                if (++$batchCount >= self::BATCH_SIZE) {
                    $pdo->commit();
                    $pdo->beginTransaction();
                    $batchCount = 0;
                }
            }

            unset($spl);
        }

        closedir($handle);

        $pdo->commit();

        foreach ($tables as $table) {
            $pdo->exec("ALTER TABLE `$table` ENABLE KEYS");
        }

        $this->jsonResponse(['success' => true, 'message' => 'Importation terminée avec succès.']);
    }

    private function prepareStatements(\PDO $pdo): array
    {
        return [
            'bureau' => $pdo->prepare(
                "INSERT IGNORE INTO Bureau (CodeBureau, NomBureau) VALUES (?, ?)"
            ),
            'operation' => $pdo->prepare(
                "INSERT IGNORE INTO Operation (CodeOperation, NomOperation) VALUES (?, ?)"
            ),
            'carnet' => $pdo->prepare(
                "INSERT IGNORE INTO Carnet (CodeBureau, Date, NomBureau, NombreCarnet) VALUES (?, ?, ?, ?)"
            ),
            'trafic_upsert' => $pdo->prepare(
                "INSERT INTO Trafic (CodeBureau, NomOperation, DateOperation, MontantReception)
                 VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE MontantReception = VALUES(MontantReception)"
            ),
            'cnep' => $pdo->prepare(
                "INSERT IGNORE INTO Cnep (CodeOperation, Date, Bureau, NomOperation, Wilaya, RPVU, RIPV, Montant)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            ),
        ];
    }

    private function cleanNum(string $value): string
    {
        return str_replace([' ', ','], ['', '.'], trim($value));
    }
}
