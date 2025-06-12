<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class StatsOverview extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Total Revenue', 'Rp ' . number_format(Order::where('status', 'completed')->sum('total_amount'), 0, ',', '.'))
        ->description('Total revenue from completed orders')
        ->descriptionIcon('heroicon-m-arrow-trending-up')
        ->color('success'),
      Stat::make('Total Orders', Order::count())
        ->description('Total number of orders')
        ->descriptionIcon('heroicon-m-shopping-cart')
        ->color('primary'),
      Stat::make('Total Users', User::count())
        ->description('Total registered users')
        ->descriptionIcon('heroicon-m-users')
        ->color('warning'),
      Stat::make('Total Products', Product::count())
        ->description('Total available products')
        ->descriptionIcon('heroicon-m-cube')
        ->color('info'),
      Stat::make('Pending Orders', Order::where('status', 'pending')->count())
        ->description('Orders waiting to be processed')
        ->descriptionIcon('heroicon-m-clock')
        ->color('warning'),
      Stat::make('Completed Orders', Order::where('status', 'completed')->count())
        ->description('Successfully delivered orders')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color('success'),
    ];
  }
}
