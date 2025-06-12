<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class OrderResource extends Resource
{
  protected static ?string $model = Order::class;

  protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

  protected static ?string $navigationGroup = 'E-commerce';

  protected static ?int $navigationSort = 2;

  protected static ?string $recordTitleAttribute = 'id';

  public static function getNavigationBadge(): ?string
  {
    return static::getModel()::where('status', 'pending')->count();
  }

  public static function getNavigationBadgeColor(): ?string
  {
    return 'warning';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Tabs::make('Order Details')
          ->tabs([
            Tab::make('Order Information')
              ->schema([
                Section::make('Basic Information')
                  ->schema([
                    Forms\Components\Select::make('status')
                      ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                        'challenge' => 'Challenge',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                      ])
                      ->required(),
                    Forms\Components\TextInput::make('total_price')
                      ->required()
                      ->numeric()
                      ->prefix('Rp'),
                    Forms\Components\Select::make('user_id')
                      ->relationship('user', 'name')
                      ->required()
                      ->searchable()
                      ->preload(),
                  ])->columns(3),
              ]),
            Tab::make('Shipping Information')
              ->schema([
                Forms\Components\TextInput::make('shipping_address')
                  ->required(),
                Forms\Components\TextInput::make('shipping_city')
                  ->required(),
                Forms\Components\TextInput::make('shipping_postal_code')
                  ->required(),
                Forms\Components\TextInput::make('shipping_phone')
                  ->tel()
                  ->required(),
              ])->columns(2),
            Tab::make('Order Notes')
              ->schema([
                RichEditor::make('notes')
                  ->toolbarButtons([
                    'bold',
                    'italic',
                    'underline',
                    'bulletList',
                    'orderedList',
                  ]),
                DateTimePicker::make('estimated_delivery')
                  ->label('Estimated Delivery Date'),
              ]),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('id')
          ->sortable()
          ->searchable()
          ->label('Order ID'),
        TextColumn::make('user.name')
          ->searchable()
          ->sortable()
          ->label('Customer'),
        TextColumn::make('total_price')
          ->money('IDR')
          ->sortable()
          ->label('Total'),
        BadgeColumn::make('status')
          ->colors([
            'warning' => 'pending',
            'success' => 'paid',
            'danger' => 'cancelled',
            'secondary' => 'challenge',
            'primary' => 'processing',
            'info' => 'shipped',
            'success' => 'delivered',
          ]),
        IconColumn::make('is_paid')
          ->boolean()
          ->label('Payment Status'),
        TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->label('Order Date'),
      ])
      ->filters([
        SelectFilter::make('status')
          ->options([
            'pending' => 'Pending',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
            'challenge' => 'Challenge',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
          ]),
        SelectFilter::make('is_paid')
          ->options([
            '1' => 'Paid',
            '0' => 'Unpaid',
          ]),
      ])
      ->actions([
        ViewAction::make()
          ->modalContent(fn(Order $record): View => view(
            'filament.resources.order-resource.pages.view-order',
            ['order' => $record]
          )),
        EditAction::make(),
        Action::make('print')
          ->icon('heroicon-o-printer')
          ->url(fn(Order $record): string => route('order.print', ['order' => $record]))
          ->openUrlInNewTab(),
        Action::make('send_invoice')
          ->icon('heroicon-o-envelope')
          ->action(fn(Order $record) => $record->sendInvoice())
          ->requiresConfirmation(),
      ])
      ->bulkActions([
        BulkAction::make('mark_as_paid')
          ->icon('heroicon-o-check')
          ->action(fn(Collection $records) => $records->each->markAsPaid())
          ->requiresConfirmation(),
        BulkAction::make('mark_as_shipped')
          ->icon('heroicon-o-truck')
          ->action(fn(Collection $records) => $records->each->markAsShipped())
          ->requiresConfirmation(),
        Tables\Actions\DeleteBulkAction::make(),
      ])
      ->defaultSort('created_at', 'desc');
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
      'index' => Pages\ListOrders::route('/'),
      'create' => Pages\CreateOrder::route('/create'),
      'edit' => Pages\EditOrder::route('/{record}/edit'),
    ];
  }
}
