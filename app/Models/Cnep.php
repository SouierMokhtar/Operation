<?php

namespace App\Models;

use App\Core\Database;

class Cnep
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByWilaya(int $wilaya = 31): array
    {
        return $this->db->fetchAll(
            "SELECT Bureau, NomOperation, Date, Wilaya, RPVU, RIPV, Montant
             FROM Cnep
             WHERE Wilaya = ?
             ORDER BY Bureau, Date",
            [$wilaya]
        );
    }

    public function getStats(array $rows): array
    {
        $totalMontant = 0;
        $totalRpvu    = 0;
        $totalRipv    = 0;

        foreach ($rows as $row) {
            $totalMontant += (float)($row['Montant'] ?? 0);
            $totalRpvu    += (int)($row['RPVU'] ?? 0);
            $totalRipv    += (int)($row['RIPV'] ?? 0);
        }

        return [
            'total_operations' => count($rows),
            'total_montant'    => $totalMontant,
            'total_rpvu'       => $totalRpvu,
            'total_ripv'       => $totalRipv,
        ];
    }
}
