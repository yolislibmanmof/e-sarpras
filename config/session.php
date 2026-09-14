<?php

return [
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'cookie'   => env('SESSION_COOKIE', 'e_sarpras_session'),
    'httponly' => true,
    'samesite' => 'Lax',
];