<?php

namespace App\Filament\Resources\StoreSettings;

use App\Filament\Resources\StoreSettings\Pages\CreateStoreSetting;
use App\Filament\Resources\StoreSettings\Pages\EditStoreSetting;
use App\Filament\Resources\StoreSettings\Pages\ListStoreSettings;
use App\Models\StoreSetting;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Store Administration';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Setting Key')
                    ->required()
                    ->maxLength(100),

                Select::make('group')
                    ->options([
                        'general' => 'General Store Configuration',
                        'localization' => 'Currency & Localization',
                        'finance' => 'Tax & Financials',
                        'shipping' => 'Shipping & Delivery Rates',
                    ])
                    ->default('general')
                    ->required(),

                Select::make('type')
                    ->options([
                        'string' => 'String / Text',
                        'float' => 'Decimal / Float',
                        'integer' => 'Integer Number',
                        'boolean' => 'Boolean True/False',
                        'json' => 'JSON Structured Data',
                    ])
                    ->default('string')
                    ->required(),

                Textarea::make('value')
                    ->label('Setting Value')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->weight('bold')
                    ->searchable()
                    ->color('primary'),

                TextColumn::make('group')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('value')
                    ->limit(50),

                TextColumn::make('type')
                    ->badge()
                    ->color('info'),

                TextColumn::make('updated_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'localization' => 'Localization',
                        'finance' => 'Finance',
                        'shipping' => 'Shipping',
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
            'index' => ListStoreSettings::route('/'),
            'create' => CreateStoreSetting::route('/create'),
            'edit' => EditStoreSetting::route('/{record}/edit'),
        ];
    }
}
