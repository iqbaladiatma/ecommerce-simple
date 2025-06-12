<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomRateLimiter
{
  protected $limiter;

  public function __construct(RateLimiter $limiter)
  {
    $this->limiter = $limiter;
  }

  public function handle(Request $request, Closure $next): Response
  {
    $key = $request->ip();

    // Rate limit by IP
    if ($this->limiter->tooManyAttempts($key, 60)) { // 60 requests per minute
      return response()->json([
        'message' => 'Too many requests. Please try again later.',
        'retry_after' => $this->limiter->availableIn($key)
      ], 429);
    }

    $this->limiter->hit($key);

    $response = $next($request);

    $response->headers->add([
      'X-RateLimit-Limit' => 60,
      'X-RateLimit-Remaining' => $this->limiter->remaining($key, 60),
    ]);

    return $response;
  }
}
