<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaymentAnalytics extends BaseWidget
{
  protected function getStats(): array
  {
    $today = now()->startOfDay();
    $thisMonth = now()->startOfMonth();

    $totalRevenue = Order::where('status', 'paid')
      ->sum('total_amount');

    $monthlyRevenue = Order::where('status', 'paid')
      ->where('created_at', '>=', $thisMonth)
      ->sum('total_amount');

    $successRate = Transaction::select(
      DB::raw('COUNT(CASE WHEN status IN ("capture", "settlement") THEN 1 END) * 100.0 / COUNT(*) as success_rate')
    )->first()->success_rate ?? 0;

    $pendingPayments = Order::where('status', 'pending')
      ->where('created_at', '>=', $today)
      ->count();

    return [
      Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
        ->description('All time revenue')
        ->descriptionIcon('heroicon-m-currency-dollar')
        ->color('success'),

      Stat::make('Monthly Revenue', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
        ->description('Revenue this month')
        ->descriptionIcon('heroicon-m-calendar')
        ->color('primary'),

      Stat::make('Payment Success Rate', number_format($successRate, 1) . '%')
        ->description('Successful payments')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color($successRate >= 90 ? 'success' : ($successRate >= 70 ? 'warning' : 'danger')),

      Stat::make('Pending Payments', $pendingPayments)
        ->description('Payments pending today')
        ->descriptionIcon('heroicon-m-clock')
        ->color('warning'),
    ];
  }
}
