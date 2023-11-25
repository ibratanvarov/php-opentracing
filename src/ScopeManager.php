<?php

declare(strict_types=1);

namespace Alifuz\Opentracing;

use DateTimeInterface;
use OpenTracing\GlobalTracer;
use OpenTracing\Scope;

class ScopeManager
{
    public function __construct(
        private Scope $scope
    ) {
    }

    public static function init(string $operationName, array $options = []): self
    {
        $scope = GlobalTracer::get()->startActiveSpan($operationName, $options);

        return new self(
            scope: $scope
        );
    }

    public function log(array $fields = [], float|DateTimeInterface|int $timestamp = null): void
    {
        $span = $this->scope->getSpan();
        $span->log($fields, $timestamp);
    }

    public function setTag(string $key, float|bool|int|string $value): void
    {
        $span = $this->scope->getSpan();
        $span->setTag($key, $value);
    }

    public function close(): void
    {
        $this->scope->close();
    }
}
