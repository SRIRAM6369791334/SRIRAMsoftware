<?php

declare(strict_types=1);

return [
    'app_name' => 'Business Management Suite',
    'base_url' => getenv('APP_URL') ?: 'http://localhost:8000',
    'session_name' => 'bms_session',
    'csrf_token_name' => '_csrf',
    'upload_max_size' => 5 * 1024 * 1024,
    'allowed_upload_types' => ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'],
];
