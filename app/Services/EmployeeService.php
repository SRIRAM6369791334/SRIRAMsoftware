<?php

declare(strict_types=1);

namespace App\Services;

use Core\Logger;

final class EmployeeService
{
    public function validate(array $payload): array
    {
        Logger::info('Employee validation executed', ['keys' => array_keys($payload)]);
        return $payload;
    }
}
