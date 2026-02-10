<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class DashboardService
{
    public function stats(): array
    {
        $db = Database::connection();
        return [
            'users' => (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'employees' => (int) $db->query('SELECT COUNT(*) FROM employees')->fetchColumn(),
            'clients' => (int) $db->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
            'open_tickets' => (int) $db->query("SELECT COUNT(*) FROM tickets WHERE status IN ('open','in_progress','waiting')")->fetchColumn(),
            'tasks_due' => (int) $db->query('SELECT COUNT(*) FROM tasks WHERE due_date = CURDATE()')->fetchColumn(),
        ];
    }
}
