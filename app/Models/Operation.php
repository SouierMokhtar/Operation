<?php

namespace App\Models;

use App\Core\Database;

class Operation
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll(
            "SELECT CodeOperation, NomOperation FROM Operation"
        );
    }

    public function count(): int
    {
        $row = $this->db->fetch("SELECT COUNT(*) AS total FROM Operation");
        return (int)($row['total'] ?? 0);
    }
}
