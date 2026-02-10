<?php

declare(strict_types=1);

namespace Core;

final class Logger
{
    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        $line = sprintf("[%s] %s %s %s\n", date('c'), $level, $message, json_encode($context, JSON_UNESCAPED_SLASHES));
        file_put_contents(__DIR__ . '/../storage/logs/app.log', $line, FILE_APPEND);
    }
}
