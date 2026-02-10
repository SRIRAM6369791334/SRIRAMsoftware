<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\BehaviorIntelligenceService;
use App\Services\DuplicateDetectionService;
use Core\Controller;
use Core\Request;

final class IntelligenceController extends Controller
{
    public function behavior(Request $request): void
    {
        $service = new BehaviorIntelligenceService();
        $this->view('advanced/behavior', [
            'heat' => $service->frictionHeat(),
        ]);
    }

    public function duplicates(Request $request): void
    {
        $service = new DuplicateDetectionService();
        $this->view('advanced/duplicates', [
            'leadDuplicates' => $service->detectDuplicateLeads(),
            'clientDuplicates' => $service->detectDuplicateClients(),
        ]);
    }
}
