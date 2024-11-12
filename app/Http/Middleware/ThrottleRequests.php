<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Middleware\ThrottleRequests as Middleware;

class ThrottleRequests extends Middleware
{
    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        }
        if ($route = $request->route()) {
            return sha1(
                implode('|', $route->methods())
                . '|' .
                implode('|', [
                    $route->getDomain(),
                    $route->uri(),
                    $request->ip(),
                ])
            );
        }

        throw new \RuntimeException('Unable to generate the request signature. Route unavailable.');
    }

    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        $limiter = app(RateLimiter::class);
        $key = $prefix.$this->resolveRequestSignature($request);

        // Проверяем оставшиеся попытки
        $remainingAttempts = $limiter->remaining($key, $maxAttempts);

        if ($remainingAttempts === 1) {
            // Положите сообщение в сессию, если осталось 1 попытка
            session()->flash('warning', mb_strtoupper('Будьте внимательны - у вас остался всего 1 запрос до выдачи вам таймаута.'));
        }

        return $this->handleRequest(
            $request,
            $next,
            [
                (object) [
                    'key' => $key,
                    'maxAttempts' => $this->resolveMaxAttempts($request, $maxAttempts),
                    'decayMinutes' => $decayMinutes,
                    'responseCallback' => null,
                ],
            ]
        );
    }
}
