<?php

declare(strict_types=1);

namespace App\Api;

use Core\Auth;
use Core\Rbac;
use Core\Response;

final class ApiController
{
    public function requirePermission(string $permission): bool
    {
        $userId = Auth::id();
        if (!$userId || !Rbac::can($userId, $permission)) {
            Response::json(['error' => 'Forbidden'], 403);
            return false;
        }
        return true;
    }
}
