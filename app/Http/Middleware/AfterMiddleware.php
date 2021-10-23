<?php

namespace App\Http\Middleware;

use App\Shared\Application\Services\Events\EventCollector;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AfterMiddleware
 *
 * @package App\Http\Middleware
 */
class AfterMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse && $response->isSuccessful()) {
            app(EventCollector::class)->fire();
        }

        return $response;
    }
}
