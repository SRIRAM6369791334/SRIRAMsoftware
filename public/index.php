<?php

declare(strict_types=1);

require __DIR__ . '/../core/Autoloader.php';

use App\Controllers\AdvancedController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ModuleController;
use App\Controllers\WorkflowController;
use App\Controllers\IntelligenceController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use Core\Request;
use Core\Router;
use Core\Security;

$appConfig = require __DIR__ . '/../config/app.php';
Security::initSession($appConfig['session_name']);

$request = new Request();
$router = new Router();

$router->add('GET', '/', [AuthController::class, 'showLogin']);
$router->add('GET', '/login', [AuthController::class, 'showLogin']);
$router->add('POST', '/login', [AuthController::class, 'login'], [CsrfMiddleware::class]);
$router->add('GET', '/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);

$router->add('GET', '/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/users', [UserController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/roles', [ModuleController::class, 'roles'], [AuthMiddleware::class]);
$router->add('GET', '/employees', [ModuleController::class, 'employees'], [AuthMiddleware::class]);
$router->add('GET', '/attendance', [ModuleController::class, 'attendance'], [AuthMiddleware::class]);
$router->add('GET', '/clients', [ModuleController::class, 'clients'], [AuthMiddleware::class]);
$router->add('GET', '/tickets', [ModuleController::class, 'tickets'], [AuthMiddleware::class]);
$router->add('GET', '/tasks', [ModuleController::class, 'tasks'], [AuthMiddleware::class]);
$router->add('GET', '/crm', [ModuleController::class, 'crm'], [AuthMiddleware::class]);
$router->add('GET', '/reports', [ModuleController::class, 'reports'], [AuthMiddleware::class]);
$router->add('GET', '/settings', [ModuleController::class, 'settings'], [AuthMiddleware::class]);
$router->add('GET', '/operations', [AdvancedController::class, 'operations'], [AuthMiddleware::class]);
$router->add('GET', '/hr-intel', [AdvancedController::class, 'hr'], [AuthMiddleware::class]);
$router->add('GET', '/sales-intel', [AdvancedController::class, 'sales'], [AuthMiddleware::class]);
$router->add('GET', '/workflows', [WorkflowController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/behavior-intel', [IntelligenceController::class, 'behavior'], [AuthMiddleware::class]);
$router->add('GET', '/duplicates', [IntelligenceController::class, 'duplicates'], [AuthMiddleware::class]);
$router->add('GET', '/workflow-simulate', [WorkflowController::class, 'simulate'], [AuthMiddleware::class]);

$router->dispatch($request);
