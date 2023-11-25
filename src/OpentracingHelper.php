<?php

declare(strict_types=1);

namespace Alifuz\Opentracing;

use const OpenTracing\Formats\HTTP_HEADERS;
use OpenTracing\GlobalTracer;
use OpenTracing\SpanContext;
use Throwable;
use const OpenTracing\Formats\TEXT_MAP;

class OpentracingHelper
{
    /**
     * Injects active span context into headers.
     */
    public static function injectHeaders(array &$carrier): array
    {
        $tracer = GlobalTracer::get();
        $span = $tracer->getActiveSpan();
        if ($span !== null) {
            $tracer->inject(
                $span->getContext(),
                HTTP_HEADERS,
                $carrier
            );
        }

        return $carrier;
    }

    public static function injectText(array &$carrier): array
    {
        $tracer = GlobalTracer::get();
        $span = $tracer->getActiveSpan();
        if ($span !== null) {
            $tracer->inject(
                $span->getContext(),
                TEXT_MAP,
                $carrier
            );
        }

        return $carrier;
    }

    /**
     * Extract span context from request.
     */
    public static function extractFromHttpRequest(): ?SpanContext
    {
        return GlobalTracer::get()->extract(
            HTTP_HEADERS,
            getallheaders()
        );
    }

    public static function extractFromFile(mixed $carrier): ?SpanContext
    {
        return GlobalTracer::get()->extract(
            TEXT_MAP,
            $carrier
        );
    }

    public static function flush()
    {
        GlobalTracer::get()->flush();
    }

    /**
     * Handle exceptions in Handler.php.
     */
    public static function handleException(Throwable $exception): void
    {
        $scope = GlobalTracer::get()->getScopeManager()->getActive();
        if ($scope !== null) {
            $span = $scope->getSpan();

            $errors = [
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];

//            if (in_array($class, $this->dontReport) === false) {
//                $errors['file'] = $exception->getFile();
//                $errors['line'] = $exception->getLine();
//            }

            $span->log($errors);
            $span->setTag('error', true);
            $scope->close();
        }
    }
}
