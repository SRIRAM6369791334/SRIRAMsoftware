<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AnalyticsService;
use App\Services\CRMIntelligenceService;
use App\Services\QueueService;
use App\Services\SmartUxService;
use Core\Controller;
use Core\Request;

final class AdvancedController extends Controller
{
    public function operations(Request $request): void
    {
        $this->view('advanced/operations', [
            'queue' => (new QueueService())->dashboardMetrics(),
            'kpis' => (new AnalyticsService())->kpiSnapshot(),
            'recommendations' => (new SmartUxService())->nextActions(),
        ]);
    }

    public function hr(Request $request): void
    {
        $this->view('advanced/hr', [
            'summary' => (new AnalyticsService())->monthlyHrSummary(),
        ]);
    }

    public function sales(Request $request): void
    {
        $this->view('advanced/sales', [
            'insights' => (new CRMIntelligenceService())->salesInsights(),
        ]);
    }
}
