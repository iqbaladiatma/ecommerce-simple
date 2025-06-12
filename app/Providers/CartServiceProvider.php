<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->singleton(CartService::class, function ($app) {
      return new CartService();
    });
  }

  public function boot()
  {
    //
  }
}
