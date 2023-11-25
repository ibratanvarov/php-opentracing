<?php

declare(strict_types=1);

namespace Alifuz\Opentracing;

use Exception;
use Jaeger\Config;
use Jaeger\Tracer;

class OpentracingFactory
{
    /**
     * @throws Exception
     */
    public static function makeTracer(): Tracer
    {
        $config = new Config(
            [
                'dispatch_mode' => Config::JAEGER_OVER_BINARY_UDP,
                'local_agent' => [
                    'reporting_host' => config('opentracing.agent_host'),
                    'reporting_port' => config('opentracing.agent_port'),
                ],
                'sampler' => [
                    'type' => config('opentracing.sampler_type'),
                    'param' => config('opentracing.sampler_param'),
                ],
                'logging' => config('opentracing.logs_enabled'),
            ],
            config('opentracing.service_name')
        );

        return $config->initializeTracer();
    }
}
