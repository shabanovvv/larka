<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Гарантирует, что каждый HTTP-запрос и ответ получают единый correlation ID для трассировки.
 */
class EnsureCorrelationId
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $config = config('correlation');
        $header = $config['header'];
        $attribute = $config['attribute'];

        $id = $request->headers->get($header) ?? ($config['generator'])();

        $request->attributes->set($attribute, $id);
        Log::withContext([$attribute => $id]);

        $response = $next($request);
        $response->headers->set($header, $id);

        return $response;
    }
}
