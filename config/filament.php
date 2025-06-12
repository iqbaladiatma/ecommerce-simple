<?php

use Filament\Middleware\Authenticate;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;

return [
  'path' => env('FILAMENT_PATH', 'admin'),
  'domain' => env('FILAMENT_DOMAIN'),
  'home_url' => env('FILAMENT_HOME_URL', '/'),
  'auth' => [
    'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
    'pages' => [
      'login' => \Filament\Pages\Auth\Login::class,
    ],
  ],
  'middleware' => [
    'auth' => [
      AdminMiddleware::class,
      Authenticate::class,
    ],
    'base' => [
      EncryptCookies::class,
      AddQueuedCookiesToResponse::class,
      StartSession::class,
      ShareErrorsFromSession::class,
      VerifyCsrfToken::class,
      SubstituteBindings::class,
    ],
  ],
  'pages' => [
    'namespace' => 'App\\Filament\\Pages',
    'path' => app_path('Filament/Pages'),
    'register' => [],
  ],
  'resources' => [
    'namespace' => 'App\\Filament\\Resources',
    'path' => app_path('Filament/Resources'),
    'register' => [],
  ],
  'widgets' => [
    'namespace' => 'App\\Filament\\Widgets',
    'path' => app_path('Filament/Widgets'),
    'register' => [],
  ],
  'livewire' => [
    'namespace' => 'App\\Filament',
    'path' => app_path('Filament'),
  ],
];
