<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
  public function handle(Request $request, Closure $next)
  {
    $response = $next($request);

    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

    // HSTS Header - Only add if using HTTPS
    if ($request->isSecure()) {
      $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
    }

    // Content Security Policy
    $csp = "default-src 'self'; " .
      "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; " .
      "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
      "img-src 'self' data: https:; " .
      "font-src 'self' https://cdn.jsdelivr.net; " .
      "connect-src 'self'; " .
      "frame-ancestors 'none'; " .
      "form-action 'self'; " .
      "base-uri 'self'; " .
      "object-src 'none'";

    $response->headers->set('Content-Security-Policy', $csp);

    return $response;
  }
}
