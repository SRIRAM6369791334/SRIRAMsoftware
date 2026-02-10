<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class AnalyticsService
{
    public function kpiSnapshot(): array
    {
        $db = Database::connection();
        return [
            'yoy_revenue_growth' => (float) $db->query('SELECT COALESCE(SUM(CASE WHEN year=YEAR(CURDATE()) THEN revenue END),0) - COALESCE(SUM(CASE WHEN year=YEAR(CURDATE())-1 THEN revenue END),0) FROM yearly_kpis')->fetchColumn(),
            'ticket_sla_breach' => (int) $db->query('SELECT COUNT(*) FROM tickets WHERE sla_due_at < NOW() AND status NOT IN (\'resolved\',\'closed\')')->fetchColumn(),
            'inactive_users_30d' => (int) $db->query('SELECT COUNT(*) FROM users WHERE last_login_at IS NULL OR last_login_at < NOW() - INTERVAL 30 DAY')->fetchColumn(),
        ];
    }

    public function monthlyHrSummary(): array
    {
        $db = Database::connection();
        return [
            'avg_performance_score' => (float) $db->query('SELECT COALESCE(AVG(score),0) FROM employee_performance WHERE review_month = DATE_FORMAT(CURDATE(), "%Y-%m-01")')->fetchColumn(),
            'wfh_days' => (int) $db->query('SELECT COUNT(*) FROM attendance WHERE status = \'wfh\' AND DATE_FORMAT(attendance_date, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m")')->fetchColumn(),
            'overtime_hours' => (float) $db->query('SELECT COALESCE(SUM(overtime_minutes),0)/60 FROM attendance WHERE DATE_FORMAT(attendance_date, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m")')->fetchColumn(),
        ];
    }
}
