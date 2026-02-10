<?php

declare(strict_types=1);

require __DIR__ . '/../core/Autoloader.php';

use App\Models\QueueJob;
use App\Services\ErrorLogService;
use Core\Database;

$jobs = (new QueueJob())->dueJobs(25);
$db = Database::connection();

foreach ($jobs as $job) {
    $db->prepare("UPDATE queue_jobs SET status='running', started_at=NOW(), attempts=attempts+1 WHERE id=:id")
        ->execute(['id' => $job['id']]);
    try {
        usleep(10000);
        $db->prepare("UPDATE queue_jobs SET status='completed', finished_at=NOW(), runtime_seconds=TIMESTAMPDIFF(SECOND, started_at, NOW()) WHERE id=:id")
            ->execute(['id' => $job['id']]);
        echo "Processed job #{$job['id']}\n";
    } catch (Throwable $e) {
        $db->prepare("UPDATE queue_jobs SET status='failed', last_error=:err WHERE id=:id")
            ->execute(['id' => $job['id'], 'err' => $e->getMessage()]);
        (new ErrorLogService())->capture('error', $e->getMessage(), 'queue-worker');
    }
}
