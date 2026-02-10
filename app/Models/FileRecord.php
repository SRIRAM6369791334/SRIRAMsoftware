<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class FileRecord extends Model
{
    public function paginate(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare('SELECT * FROM files ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
