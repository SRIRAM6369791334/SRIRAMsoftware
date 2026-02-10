<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class QueueJob extends Model
{
    public function dueJobs(int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT * FROM queue_jobs WHERE status='queued' AND run_at <= NOW() ORDER BY id ASC LIMIT :limit");
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
