<?php

declare(strict_types=1);

namespace Core;

final class Security
{
    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function initSession(string $sessionName): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($sessionName);
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Strict',
                'secure' => isset($_SERVER['HTTPS']),
            ]);
            session_start();
        }
    }
}
