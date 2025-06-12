<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';

  protected static ?string $navigationGroup = 'Shop Management';

  protected static ?int $navigationSort = 3;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('email')
          ->email()
          ->required()
          ->maxLength(255)
          ->unique(ignoreRecord: true),
        Forms\Components\TextInput::make('phone')
          ->tel()
          ->maxLength(255),
        Forms\Components\TextInput::make('address')
          ->maxLength(255),
        Forms\Components\DateTimePicker::make('last_login_at')
          ->label('Last Login')
          ->readonly(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('phone')
          ->searchable(),
        Tables\Columns\TextColumn::make('address')
          ->searchable()
          ->limit(30),
        Tables\Columns\TextColumn::make('orders_count')
          ->counts('orders')
          ->label('Total Orders')
          ->sortable(),
        Tables\Columns\TextColumn::make('last_login_at')
          ->label('Last Login')
          ->dateTime()
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Tables\Filters\SelectFilter::make('has_orders')
          ->label('Has Orders')
          ->options([
            'yes' => 'Yes',
            'no' => 'No',
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query->when(
              $data['value'] === 'yes',
              fn(Builder $query): Builder => $query->has('orders'),
            )->when(
              $data['value'] === 'no',
              fn(Builder $query): Builder => $query->doesntHave('orders'),
            );
          }),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
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
      'index' => Pages\ListCustomers::route('/'),
      'create' => Pages\CreateCustomer::route('/create'),
      'edit' => Pages\EditCustomer::route('/{record}/edit'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()
      ->whereDoesntHave('roles', function ($query) {
        $query->where('name', 'admin');
      });
  }
}
