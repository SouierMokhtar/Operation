<?php

namespace App\Models;

use App\Core\Database;

class Carnet
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByWilaya(string $wilayaPrefix = '31'): array
    {
        return $this->db->fetchAll(
            "SELECT CodeBureau, NomBureau, Date, NombreCarnet
             FROM Carnet
             WHERE CodeBureau LIKE ?",
            [$wilayaPrefix . '%']
        );
    }

    public function getTotalCarnets(array $rows): int
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += (int)$row['NombreCarnet'];
        }
        return $total;
    }
}
