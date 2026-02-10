<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\WorkflowEngineService;
use Core\Controller;
use Core\Request;

final class WorkflowController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('advanced/workflows');
    }

    public function simulate(Request $request): void
    {
        $event = (string) $request->input('event', 'ticket.created');
        $context = ['priority' => (string) $request->input('priority', 'high')];
        $result = (new WorkflowEngineService())->execute($event, $context, true);
        \Core\Response::json(['simulation' => $result]);
    }
}
