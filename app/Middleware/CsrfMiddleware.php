<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Csrf;
use Core\Request;

final class CsrfMiddleware
{
    public function handle(Request $request): void
    {
        if ($request->method() === 'POST') {
            $token = $request->input('_csrf', '');
            if (!Csrf::validate((string) $token)) {
                http_response_code(419);
                exit('Invalid CSRF token');
            }
        }
    }
}
