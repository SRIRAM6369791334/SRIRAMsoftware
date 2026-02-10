<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class CRMIntelligenceService
{
    public function leadScore(array $lead): int
    {
        $score = 0;
        $score += !empty($lead['email']) ? 15 : 0;
        $score += !empty($lead['phone']) ? 15 : 0;
        $score += in_array(($lead['status'] ?? ''), ['qualified', 'proposal'], true) ? 30 : 0;
        $score += !empty($lead['next_follow_up']) ? 20 : 0;
        $score += !empty($lead['source']) ? 20 : 0;
        return min(100, $score);
    }

    public function duplicateTicketLikely(string $title, int $clientId): bool
    {
        $sql = 'SELECT COUNT(*) FROM tickets WHERE client_id=:client_id AND title=:title AND created_at >= NOW() - INTERVAL 30 DAY';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['client_id' => $clientId, 'title' => $title]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function salesInsights(): array
    {
        $db = Database::connection();
        return [
            'lead_source_roi' => $db->query('SELECT source, SUM(revenue_amount) revenue, SUM(cost_amount) cost FROM lead_source_roi GROUP BY source')->fetchAll(),
            'deal_probability_avg' => (float) $db->query('SELECT COALESCE(AVG(probability_percent),0) FROM deals')->fetchColumn(),
            'lost_deals_last_30d' => (int) $db->query("SELECT COUNT(*) FROM deals WHERE stage='lost' AND closed_at >= NOW() - INTERVAL 30 DAY")->fetchColumn(),
        ];
    }
}
