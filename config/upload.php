<?php

return [
    'max_size_mb'          => (int) env('MAX_UPLOAD_MB', 2),
    'allowed_extensions'   => ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
    'base_path'            => dirname(__DIR__) . '/storage/uploads',
];