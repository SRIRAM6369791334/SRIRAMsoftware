<?php

declare(strict_types=1);

return [
    'read' => [
        'host' => getenv('DB_READ_HOST') ?: (getenv('DB_HOST') ?: '127.0.0.1'),
        'port' => getenv('DB_READ_PORT') ?: (getenv('DB_PORT') ?: '3306'),
        'dbname' => getenv('DB_READ_NAME') ?: (getenv('DB_NAME') ?: 'business_suite'),
        'username' => getenv('DB_READ_USER') ?: (getenv('DB_USER') ?: 'root'),
        'password' => getenv('DB_READ_PASS') ?: (getenv('DB_PASS') ?: ''),
    ],
    'write' => [
        'host' => getenv('DB_WRITE_HOST') ?: (getenv('DB_HOST') ?: '127.0.0.1'),
        'port' => getenv('DB_WRITE_PORT') ?: (getenv('DB_PORT') ?: '3306'),
        'dbname' => getenv('DB_WRITE_NAME') ?: (getenv('DB_NAME') ?: 'business_suite'),
        'username' => getenv('DB_WRITE_USER') ?: (getenv('DB_USER') ?: 'root'),
        'password' => getenv('DB_WRITE_PASS') ?: (getenv('DB_PASS') ?: ''),
    ],
    'charset' => 'utf8mb4',
];
