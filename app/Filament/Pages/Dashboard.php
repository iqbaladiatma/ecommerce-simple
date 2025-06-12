<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentOrders;
use App\Filament\Widgets\SalesChart;

class Dashboard extends Page
{
  protected static ?string $navigationIcon = 'heroicon-o-home';

  protected static string $view = 'filament.pages.dashboard';

  protected function getHeaderWidgets(): array
  {
    return [
      StatsOverview::class,
      RecentOrders::class,
      SalesChart::class,
    ];
  }
}
