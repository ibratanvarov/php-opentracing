<?php

declare(strict_types=1);

namespace Alifuz\Opentracing\Providers;

use Alifuz\Opentracing\OpentracingFactory;
use Illuminate\Support\ServiceProvider;
use OpenTracing\GlobalTracer;

class OpentracingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/opentracing.php',
            'opentracing'
        );
    }

    public function boot(): void
    {
        if (config('opentracing.enabled') === true) {
            OpentracingFactory::makeTracer();

            register_shutdown_function(function () {
                $tracer = GlobalTracer::get();
                $activeScope = $tracer->getScopeManager()->getActive();
                $activeScope?->close();

                GlobalTracer::get()->flush();
            });
        }
    }
}
