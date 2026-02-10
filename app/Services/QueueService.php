<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class QueueService
{
    public function enqueue(string $queueName, string $jobType, array $payload, ?string $runAt = null): int
    {
        $sql = 'INSERT INTO queue_jobs (queue_name, job_type, payload_json, status, run_at, created_at)
                VALUES (:queue_name, :job_type, :payload_json, :status, :run_at, NOW())';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'queue_name' => $queueName,
            'job_type' => $jobType,
            'payload_json' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'status' => 'queued',
            'run_at' => $runAt ?: date('Y-m-d H:i:s'),
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    public function dashboardMetrics(): array
    {
        $db = Database::connection();
        return [
            'queued' => (int) $db->query("SELECT COUNT(*) FROM queue_jobs WHERE status='queued'")->fetchColumn(),
            'running' => (int) $db->query("SELECT COUNT(*) FROM queue_jobs WHERE status='running'")->fetchColumn(),
            'failed' => (int) $db->query("SELECT COUNT(*) FROM queue_jobs WHERE status='failed'")->fetchColumn(),
            'avg_runtime_sec' => (float) $db->query("SELECT COALESCE(AVG(runtime_seconds),0) FROM queue_jobs WHERE status='completed'")->fetchColumn(),
        ];
    }
}
