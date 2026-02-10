<?php

declare(strict_types=1);

namespace App\Services;

use Core\Logger;

final class FileService
{
    public function validate(array $payload): array
    {
        Logger::info('File validation executed', ['keys' => array_keys($payload)]);
        return $payload;
    }
}
