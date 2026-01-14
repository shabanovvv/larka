<?php

return [
    'default' => 'deepseek',

    'reviewers' => [
        'deepseek' => [
            'driver' => Salavey\AiCodeReviewer\Adapters\DeepSeekAdapter::class,
            'client' => Salavey\AiCodeReviewer\Clients\DeepSeekClient::class,
            'enabled' => true,
            'timeout' => 60,
            'api_key' => env('DEEPSEEK_API_KEY'),
            'base_url' => 'https://api.deepseek.com',
        ],
    ],
];
