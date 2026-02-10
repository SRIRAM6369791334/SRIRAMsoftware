<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class BehaviorIntelligenceService
{
    public function personaForUser(int $userId): string
    {
        $stmt = Database::readConnection()->prepare('SELECT COUNT(*) FROM user_behavior_events WHERE user_id = :user_id AND created_at >= NOW() - INTERVAL 30 DAY');
        $stmt->execute(['user_id' => $userId]);
        $count = (int) $stmt->fetchColumn();

        if ($count > 800) return 'power_operator';
        if ($count > 250) return 'focused_user';
        if ($count > 50) return 'casual_user';
        return 'inactive_user';
    }

    public function inactivityDecayScore(int $userId): float
    {
        $stmt = Database::readConnection()->prepare('SELECT TIMESTAMPDIFF(DAY, MAX(created_at), NOW()) FROM user_behavior_events WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $days = (int) ($stmt->fetchColumn() ?: 60);
        return max(0.0, 100 - ($days * 2.5));
    }

    public function anomalyFlags(int $userId): array
    {
        $flags = [];
        $stmt = Database::readConnection()->prepare('SELECT COUNT(*) FROM user_behavior_events WHERE user_id=:uid AND event_type="auth_failed" AND created_at >= NOW() - INTERVAL 1 DAY');
        $stmt->execute(['uid' => $userId]);
        if ((int) $stmt->fetchColumn() >= 5) {
            $flags[] = 'multiple_auth_failures';
        }
        return $flags;
    }

    public function frictionHeat(): array
    {
        return Database::readConnection()->query('SELECT module_name, COUNT(*) total_events FROM user_behavior_events GROUP BY module_name ORDER BY total_events DESC LIMIT 10')->fetchAll();
    }
}
