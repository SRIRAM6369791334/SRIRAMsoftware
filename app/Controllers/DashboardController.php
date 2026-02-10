<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\DashboardService;
use Core\Controller;
use Core\Request;

final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('dashboard/index', ['stats' => (new DashboardService())->stats()]);
    }
}
