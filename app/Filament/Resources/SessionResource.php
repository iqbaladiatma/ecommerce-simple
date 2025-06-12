<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SessionResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;

class SessionResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

  protected static ?string $navigationGroup = 'User Management';

  protected static ?int $navigationSort = 2;

  protected static ?string $navigationLabel = 'Active Sessions';

  public static function table(Table $table): Table
  {
    return $table
      ->query(
        User::query()
          ->whereNotNull('last_login_at')
          ->orderBy('last_login_at', 'desc')
      )
      ->columns([
        TextColumn::make('name')
          ->searchable()
          ->sortable(),
        TextColumn::make('email')
          ->searchable()
          ->sortable(),
        TextColumn::make('last_login_at')
          ->dateTime()
          ->sortable()
          ->label('Last Login'),
        TextColumn::make('last_login_ip')
          ->label('IP Address'),
        IconColumn::make('is_online')
          ->boolean()
          ->label('Online')
          ->getStateUsing(fn($record) => $record->last_login_at->diffInMinutes(now()) < 5),
      ])
      ->filters([
        SelectFilter::make('is_online')
          ->options([
            '1' => 'Online',
            '0' => 'Offline',
          ])
          ->query(function ($query, array $data) {
            if ($data['value'] === '1') {
              return $query->where('last_login_at', '>=', now()->subMinutes(5));
            }
            return $query->where('last_login_at', '<', now()->subMinutes(5));
          }),
      ])
      ->actions([
        Action::make('force_logout')
          ->icon('heroicon-o-x-circle')
          ->color('danger')
          ->action(function ($record) {
            DB::table('sessions')
              ->where('user_id', $record->id)
              ->delete();
          })
          ->requiresConfirmation(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\BulkAction::make('force_logout_all')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->action(function ($records) {
              DB::table('sessions')
                ->whereIn('user_id', $records->pluck('id'))
                ->delete();
            })
            ->requiresConfirmation(),
        ]),
      ]);
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListSessions::route('/'),
    ];
  }
}
