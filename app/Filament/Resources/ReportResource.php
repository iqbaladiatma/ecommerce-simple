<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
  protected static ?string $model = Order::class;

  protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

  protected static ?string $navigationGroup = 'Shop Management';

  protected static ?int $navigationSort = 4;

  protected static ?string $navigationLabel = 'Sales Reports';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\DatePicker::make('start_date')
          ->required(),
        Forms\Components\DatePicker::make('end_date')
          ->required(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('created_at')
          ->label('Date')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('order_number')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('user.name')
          ->label('Customer')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('total_amount')
          ->money('IDR')
          ->sortable(),
        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
          }),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\SelectFilter::make('status')
          ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
          ]),
        Tables\Filters\Filter::make('date_range')
          ->form([
            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('end_date'),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['start_date'],
                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
              )
              ->when(
                $data['end_date'],
                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
              );
          }),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\ExportBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListReports::route('/'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()
      ->where('status', 'completed');
  }
}
