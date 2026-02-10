<?php

declare(strict_types=1);

require __DIR__ . '/../core/Autoloader.php';

use App\Api\ApiController;
use App\Services\AnalyticsService;
use App\Services\BehaviorIntelligenceService;
use App\Services\DuplicateDetectionService;
use App\Services\QueueService;
use App\Services\SmartUxService;
use App\Services\WorkflowEngineService;
use Core\Response;
use Core\Security;

$appConfig = require __DIR__ . '/../config/app.php';
Security::initSession($appConfig['session_name']);

$api = new ApiController();
$resource = $_GET['resource'] ?? '';

if ($resource === 'dashboard') {
    if (!$api->requirePermission('dashboard.view')) {
        exit;
    }
    Response::json(['ok' => true, 'resource' => 'dashboard']);
    exit;
}

if ($resource === 'operations-metrics') {
    Response::json([
        'queue' => (new QueueService())->dashboardMetrics(),
        'kpi' => (new AnalyticsService())->kpiSnapshot(),
    ]);
    exit;
}

if ($resource === 'workflow-simulate') {
    $event = (string) ($_GET['event'] ?? 'ticket.created');
    $priority = (string) ($_GET['priority'] ?? 'high');
    Response::json(['result' => (new WorkflowEngineService())->execute($event, ['priority' => $priority], true)]);
    exit;
}

if ($resource === 'duplicates') {
    $svc = new DuplicateDetectionService();
    Response::json(['leads' => $svc->detectDuplicateLeads(), 'clients' => $svc->detectDuplicateClients()]);
    exit;
}

if ($resource === 'behavior-snapshot') {
    $uid = (int) ($_GET['user_id'] ?? 0);
    $svc = new BehaviorIntelligenceService();
    Response::json([
        'persona' => $svc->personaForUser($uid),
        'inactivity_decay_score' => $svc->inactivityDecayScore($uid),
        'flags' => $svc->anomalyFlags($uid),
    ]);
    exit;
}

if ($resource === 'next-actions') {
    Response::json(['actions' => (new SmartUxService())->nextActions()]);
    exit;
}

if ($resource === 'command-search') {
    $q = strtolower(trim((string) ($_GET['q'] ?? '')));
    $commands = [
        ['label' => 'Go to Tickets', 'path' => '/tickets'],
        ['label' => 'Go to Attendance', 'path' => '/attendance'],
        ['label' => 'Go to Sales Intel', 'path' => '/sales-intel'],
        ['label' => 'Open Operations', 'path' => '/operations'],
        ['label' => 'Open Workflows', 'path' => '/workflows'],
        ['label' => 'Open Duplicates', 'path' => '/duplicates'],
    ];
    $filtered = array_values(array_filter($commands, static fn ($c) => $q === '' || str_contains(strtolower($c['label']), $q)));
    Response::json(['results' => $filtered]);
    exit;
}

Response::json(['error' => 'Not Found'], 404);
