<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class RecentOrders extends BaseWidget
{
  protected static ?string $heading = 'Recent Orders';

  protected int|string|array $columnSpan = 'full';

  public function getTableQuery(): \Illuminate\Database\Eloquent\Builder
  {
    return Order::query()
      ->latest()
      ->limit(5);
  }

  protected function getTableColumns(): array
  {
    return [
      TextColumn::make('id')
        ->label('Order ID'),
      TextColumn::make('user.name')
        ->label('Customer'),
      TextColumn::make('total_price')
        ->money('IDR'),
      BadgeColumn::make('status')
        ->colors([
          'warning' => 'pending',
          'success' => 'paid',
          'danger' => 'cancelled',
        ]),
      TextColumn::make('created_at')
        ->dateTime(),
    ];
  }
}
