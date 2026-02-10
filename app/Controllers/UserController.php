<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Core\Request;

final class UserController extends Controller
{
    public function index(Request $request): void
    {
        $page = max(1, (int) $request->input('page', 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $users = (new User())->all($limit, $offset);

        $this->view('users/index', ['users' => $users, 'page' => $page]);
    }
}
