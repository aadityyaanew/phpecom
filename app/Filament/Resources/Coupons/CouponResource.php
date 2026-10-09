<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Marketing & Coupons';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Promo Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Select::make('type')
                    ->options([
                        'percentage' => 'Percentage Discount (%)',
                        'fixed' => 'Fixed Dollar Off ($)',
                    ])
                    ->default('percentage')
                    ->required(),

                TextInput::make('value')
                    ->label('Discount Value')
                    ->numeric()
                    ->required(),

                TextInput::make('min_order_amount')
                    ->label('Minimum Order Amount')
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('max_discount_amount')
                    ->label('Maximum Discount Cap')
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('usage_limit')
                    ->label('Total Uses Allowed')
                    ->numeric()
                    ->placeholder('Unlimited if blank'),

                TextInput::make('used_count')
                    ->label('Redemption Count')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                DateTimePicker::make('starts_at')
                    ->label('Valid From'),

                DateTimePicker::make('expires_at')
                    ->label('Expires At'),

                Toggle::make('is_active')
                    ->label('Coupon Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->weight('bold')
                    ->searchable()
                    ->copyable()
                    ->color('primary'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => $state === 'percentage' ? 'info' : 'success'),

                TextColumn::make('value')
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage' ? "{$record->value}%" : "\${$record->value}")
                    ->weight('bold'),

                TextColumn::make('min_order_amount')
                    ->label('Min Spend')
                    ->money('USD')
                    ->placeholder('None'),

                TextColumn::make('used_count')
                    ->label('Usage')
                    ->formatStateUsing(fn ($record) => $record->usage_limit ? "{$record->used_count} / {$record->usage_limit}" : "{$record->used_count} / ∞"),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('expires_at')
                    ->dateTime('M d, Y')
                    ->placeholder('Never')
                    ->sortable(),
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
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
