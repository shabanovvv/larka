<?php

use Illuminate\Support\Str;

return [
    'header' => 'X-Correlation-ID',
    'attribute' => 'correlation_id',
    'generator' => fn () => (string) Str::uuid(),
];
