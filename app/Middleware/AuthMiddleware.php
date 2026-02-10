<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Auth;
use Core\Request;
use Core\Response;

final class AuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::id()) {
            Response::redirect('/login');
        }
    }
}
