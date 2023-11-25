<?php

namespace Alifuz\Opentracing\Middlewares;

use Alifuz\Opentracing\OpentracingHelper;
use Alifuz\Opentracing\ScopeManager;
use Closure;
use Exception;
use Illuminate\Http\Request;

class StartOpentracingMiddleware
{
    /**
     * Handle incoming request and start a trace if the request contains a trace context.
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $options = [
            'tags' => ['url_path' => $request->decodedPath()],
        ];

        $context = OpentracingHelper::extractFromHttpRequest();

        if ($context !== null) {
            $options['child_of'] = $context;
        }

        $scopeManager = ScopeManager::init(
            strtoupper($request->getMethod()) . ' ' . $request->decodedPath(),
            $options
        );

        $scopeManager->log([
            'headers' => $request->headers,
        ]);

        $result = $next($request);

        $scopeManager->close();

        return $result;
    }
}
