<?php

declare(strict_types=1);

return [
    'enabled' => env('JAEGER_ENABLED'),
    'service_name' => env('JAEGER_SERVICE_NAME'),
    'agent_host' => env('JAEGER_AGENT_HOST'),
    'agent_port' => env('JAEGER_AGENT_PORT'),
    'sampler_type' => env('JAEGER_SAMPLER_TYPE', 'const'),
    'sampler_param' => env('JAEGER_SAMPLER_PARAM', 1),
    'logs_enabled' => env('JAEGER_LOGS_ENABLED', false),
    'logs_channel' => env('JAEGER_LOGS_CHANNEL', 'opentracing'),
];
