<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget as BaseWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class SalesChart extends BaseWidget
{
  protected static ?string $heading = 'Sales Chart';

  protected function getData(): array
  {
    $data = Order::where('status', 'completed')
      ->whereBetween('created_at', [now()->subDays(30), now()])
      ->groupBy('date')
      ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
      ->get();

    return [
      'datasets' => [
        [
          'label' => 'Sales',
          'data' => $data->pluck('total')->toArray(),
          'borderColor' => '#9333EA',
        ],
      ],
      'labels' => $data->pluck('date')->toArray(),
    ];
  }

  protected function getType(): string
  {
    return 'line';
  }
}
