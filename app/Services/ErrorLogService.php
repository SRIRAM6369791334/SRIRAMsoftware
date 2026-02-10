<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class ErrorLogService
{
    public function capture(string $severity, string $message, string $module, ?int $userId = null): void
    {
        $sql = 'INSERT INTO error_events (severity, message, module_name, user_id, ip_address, created_at)
                VALUES (:severity, :message, :module_name, :user_id, :ip_address, NOW())';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute([
            'severity' => $severity,
            'message' => $message,
            'module_name' => $module,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    public function dashboard(): array
    {
        $db = Database::connection();
        return [
            'today_errors' => (int) $db->query('SELECT COUNT(*) FROM error_events WHERE DATE(created_at)=CURDATE()')->fetchColumn(),
            'critical_errors' => (int) $db->query("SELECT COUNT(*) FROM error_events WHERE severity='critical' AND created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn(),
        ];
    }
}
