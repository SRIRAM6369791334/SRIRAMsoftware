<?php

declare(strict_types=1);

namespace App\Services;

final class SmartUxService
{
    public function nextActions(): array
    {
        return [
            'Approve pending attendance anomalies',
            'Follow up on high-score leads with no activity in 3 days',
            'Review overdue critical tickets and assign escalation owner',
        ];
    }

    public function smartDefaults(string $module): array
    {
        return match ($module) {
            'tickets' => ['status' => 'open', 'priority' => 'medium'],
            'tasks' => ['status' => 'todo', 'priority' => 'medium'],
            default => ['sort' => 'latest'],
        };
    }
}
