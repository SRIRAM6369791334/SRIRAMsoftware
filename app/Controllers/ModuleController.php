<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Request;

final class ModuleController extends Controller
{
    public function employees(Request $request): void { $this->view('employees/index'); }
    public function attendance(Request $request): void { $this->view('attendance/index'); }
    public function clients(Request $request): void { $this->view('clients/index'); }
    public function tickets(Request $request): void { $this->view('tickets/index'); }
    public function tasks(Request $request): void { $this->view('tasks/index'); }
    public function crm(Request $request): void { $this->view('crm/index'); }
    public function reports(Request $request): void { $this->view('reports/index'); }
    public function settings(Request $request): void { $this->view('settings/index'); }
    public function roles(Request $request): void { $this->view('roles/index'); }
}
