<?php

namespace App\Models;

use App\Core\Database;

class Bureau
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll(
            "SELECT CodeGestionnaire, CodeBureau, NomBureau FROM Bureau"
        );
    }

    public function getByCode(string $code): array|false
    {
        return $this->db->fetch(
            "SELECT CodeGestionnaire, CodeBureau, NomBureau FROM Bureau WHERE CodeBureau = ?",
            [$code]
        );
    }

    public function count(): int
    {
        $row = $this->db->fetch("SELECT COUNT(*) AS total FROM Bureau");
        return (int)($row['total'] ?? 0);
    }
}
