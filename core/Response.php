<?php

declare(strict_types=1);

namespace Core;

final class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    public static function redirect(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
