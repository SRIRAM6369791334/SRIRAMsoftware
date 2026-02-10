<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

final class AuthService
{
    public function attemptLogin(string $email, string $password): ?array
    {
        $user = (new User())->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }
        if ($user['status'] !== 'active') {
            return null;
        }
        return $user;
    }
}
