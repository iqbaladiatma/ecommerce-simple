<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';

  protected static ?string $navigationGroup = 'User Management';

  protected static ?int $navigationSort = 1;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make('User Information')
          ->schema([
            Grid::make(2)
              ->schema([
                TextInput::make('name')
                  ->required()
                  ->maxLength(255),
                TextInput::make('email')
                  ->email()
                  ->required()
                  ->maxLength(255)
                  ->unique(ignoreRecord: true),
                TextInput::make('password')
                  ->password()
                  ->dehydrateStateUsing(fn($state) => Hash::make($state))
                  ->required(fn(string $context): bool => $context === 'create')
                  ->maxLength(255),
                Select::make('roles')
                  ->multiple()
                  ->relationship('roles', 'name')
                  ->preload()
                  ->visible(fn() => auth()->user()->hasRole('admin')),
                Toggle::make('is_active')
                  ->label('Active')
                  ->default(true),
              ]),
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->searchable()
          ->sortable(),
        TextColumn::make('email')
          ->searchable()
          ->sortable(),
        BadgeColumn::make('roles.name')
          ->colors([
            'primary' => 'admin',
            'success' => 'user',
          ]),
        IconColumn::make('is_active')
          ->boolean()
          ->label('Status'),
        TextColumn::make('last_login_at')
          ->dateTime()
          ->sortable()
          ->label('Last Login'),
        TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->label('Registered'),
      ])
      ->filters([
        SelectFilter::make('roles')
          ->relationship('roles', 'name'),
        SelectFilter::make('is_active')
          ->options([
            '1' => 'Active',
            '0' => 'Inactive',
          ]),
      ])
      ->actions([
        EditAction::make(),
        DeleteAction::make(),
        Action::make('impersonate')
          ->icon('heroicon-o-user')
          ->action(fn(User $record) => auth()->user()->impersonate($record))
          ->requiresConfirmation(),
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
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
