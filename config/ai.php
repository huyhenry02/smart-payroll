<?php

return [
    'face' => [
        'base_url' => env('AI_FACE_BASE_URL', 'http://127.0.0.1:8000'),
        'timeout'  => (int) env('AI_FACE_TIMEOUT', 30),
        'threshold'=> (float) env('AI_FACE_THRESHOLD', 0.5),
        'top_k'    => (int) env('AI_FACE_TOP_K', 5),
        'api_key'  => env('AI_FACE_API_KEY', null),
    ],
];
