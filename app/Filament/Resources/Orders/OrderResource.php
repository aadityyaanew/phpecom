<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|UnitEnum|null $navigationGroup = 'Sales & Orders';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Order Number')
                    ->required()
                    ->disabled(),

                Select::make('user_id')
                    ->label('Customer Account')
                    ->relationship('user', 'name')
                    ->searchable(),

                TextInput::make('customer_name')
                    ->required(),

                TextInput::make('customer_email')
                    ->email()
                    ->required(),

                TextInput::make('customer_phone')
                    ->tel(),

                Select::make('status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending Confirmation',
                        'processing' => 'Processing in Warehouse',
                        'shipped' => 'Shipped / In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending Payment',
                        'paid' => 'Paid / Authorized',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),

                TextInput::make('payment_method')
                    ->disabled(),

                TextInput::make('payment_reference')
                    ->label('Payment Transaction Ref'),

                Select::make('shipping_method')
                    ->options([
                        'standard' => 'Standard Ground',
                        'express' => 'Express Air',
                        'priority' => 'Priority Overnight',
                    ]),

                TextInput::make('carrier')
                    ->label('Logistics Courier')
                    ->placeholder('e.g. FedEx, DHL, UPS'),

                TextInput::make('tracking_number')
                    ->label('Tracking Number')
                    ->placeholder('e.g. ZYR-FDX-99281'),

                DatePicker::make('estimated_delivery')
                    ->label('Estimated Delivery Date'),

                TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                TextInput::make('discount_amount')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),

                TextInput::make('coupon_code')
                    ->label('Applied Coupon'),

                TextInput::make('shipping_rate')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),

                TextInput::make('tax_amount')
                    ->numeric()
                    ->prefix('$')
                    ->default(0),

                TextInput::make('total')
                    ->numeric()
                    ->prefix('$')
                    ->required(),

                Textarea::make('customer_notes')
                    ->columnSpanFull()
                    ->rows(2),

                Textarea::make('admin_notes')
                    ->columnSpanFull()
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (Order $record): string => $record->customer_email),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivered' => 'success',
                        'shipped' => 'primary',
                        'processing' => 'info',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('carrier')
                    ->label('Courier')
                    ->placeholder('—'),

                TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->placeholder('—')
                    ->copyable(),

                TextColumn::make('total')
                    ->money('USD')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Placed At')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
