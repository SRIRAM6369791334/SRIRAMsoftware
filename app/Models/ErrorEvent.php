<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class ErrorEvent extends Model
{
    public function latest(int $limit = 100): array
    {
        $stmt = $this->db->prepare('SELECT * FROM error_events ORDER BY id DESC LIMIT :limit');
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
