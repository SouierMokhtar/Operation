<?php

namespace App\Models;

use App\Core\Database;

class Trafic
{
    private Database $db;

    private string $bureauxList = "'3110 00','3110 01','3118 00','3134 53','3110 05','3120 34','3110 03','3110 72','3110 80','3110 04','3120 39','3134 15','3136 35','3135 71','3110 19','3110 13','3148 39','3110 06','3110 07','3110 29','3120 35','3120 32','3134 14','3120 04','3114 18','3117 61','3110 70','3120 05','3120 31','3138 22','3139 51','3110 68','3109 23','3137 75','3113 20','3137 76','3117 72','3114 19','3121 03','3120 57','3110 62','3121 02','3120 36','3110 39','3120 20','3110 28','3145 33','3113 47','3114 17','3138 14','3139 72','3140 66','3115 44','3115 41','3115 45','3135 70','3109 25','3113 92','3136 52','3140 68','3145 81','3133 12','3113 48','3139 29','3140 69','3109 24','3115 40','3111 56','3115 59','3121 79','3132 01','3115 66','3141 77','3135 65','3105 98','3129 96','3135 98','3136 53','3140 70','3137 74','3138 62','3139 73','3140 44','3141 65','3141 66','3132 05','3115 58','3109 20','3112 37','3135 66','3131 99','3115 61','3112 18','3110 74','3109 22','3115 43','3101 99','3115 42','3129 97','3129 99','3132 02','3136 54','3143 17','3141 78','3115 63','3112 26','3103 55','3129 98','3115 62','3136 55','3141 64','3143 73','3142 29','3143 70','3142 30','3143 16','3144 12','3143 74','3145 05','3148 81','3146 34','3145 74','3145 58','3146 33','3149 10','3148 31','3145 80','3145 72','3145 90','3146 32','3146 42','3146 43','3147 52','3147 53','3148 76','3148 82','3148 75'";

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getDetail(): array
    {
        $sql = "SELECT Trafic.CodeBureau, NomBureau, DateOperation, NomOperation,
                       NombreReception, MontantReception, Droit, REMB, NBRREMB
                FROM Bureau
                INNER JOIN Trafic ON Bureau.CodeBureau = Trafic.CodeBureau
                WHERE Bureau.CodeBureau IN ({$this->bureauxList})
                ORDER BY Trafic.CodeBureau, DateOperation";

        return $this->db->fetchAll($sql);
    }

    public function getGlobal(): array
    {
        $sql = "SELECT Trafic.CodeBureau, NomBureau, NomOperation,
                       SUM(NombreReception) AS NombreOperations,
                       SUM(MontantReception) AS MontantOperations,
                       SUM(Droit) AS Droit,
                       SUM(REMB) AS Remboursement,
                       SUM(NBRREMB) AS NombreRemboursements
                FROM Bureau
                INNER JOIN Trafic ON Bureau.CodeBureau = Trafic.CodeBureau
                WHERE Bureau.CodeBureau IN ({$this->bureauxList})
                GROUP BY Trafic.CodeBureau, NomOperation";

        return $this->db->fetchAll($sql);
    }

    public function getDetailStats(array $rows): array
    {
        $stats = [
            'total_lines'    => count($rows),
            'total_ops'      => 0,
            'total_montant'  => 0,
            'total_droit'    => 0,
            'total_remb'     => 0,
            'total_nbr_remb' => 0,
        ];

        foreach ($rows as $row) {
            $stats['total_ops']      += (float)($row['NombreReception'] ?? 0);
            $stats['total_montant']  += (float)($row['MontantReception'] ?? 0);
            $stats['total_droit']    += (float)($row['Droit'] ?? 0);
            $stats['total_remb']     += (float)($row['REMB'] ?? 0);
            $stats['total_nbr_remb'] += (float)($row['NBRREMB'] ?? 0);
        }

        return $stats;
    }

    public function getGlobalStats(array $rows): array
    {
        $stats = [
            'total_lines'    => count($rows),
            'total_ops'      => 0,
            'total_montant'  => 0,
            'total_droit'    => 0,
            'total_remb'     => 0,
            'total_nbr_remb' => 0,
        ];

        foreach ($rows as $row) {
            $stats['total_ops']      += (float)($row['NombreOperations'] ?? 0);
            $stats['total_montant']  += (float)($row['MontantOperations'] ?? 0);
            $stats['total_droit']    += (float)($row['Droit'] ?? 0);
            $stats['total_remb']     += (float)($row['Remboursement'] ?? 0);
            $stats['total_nbr_remb'] += (float)($row['NombreRemboursements'] ?? 0);
        }

        return $stats;
    }
}
