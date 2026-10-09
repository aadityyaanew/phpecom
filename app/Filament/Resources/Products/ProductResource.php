<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(50),

                TextInput::make('barcode')
                    ->maxLength(50),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                TextInput::make('compare_at_price')
                    ->numeric()
                    ->prefix('$')
                    ->helperText('Original MSRP before sale discount'),

                TextInput::make('cost_price')
                    ->numeric()
                    ->prefix('$')
                    ->helperText('Internal production cost'),

                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),

                TextInput::make('low_stock_threshold')
                    ->required()
                    ->numeric()
                    ->default(5),

                TextInput::make('featured_image')
                    ->label('Image URL')
                    ->url()
                    ->helperText('Direct image URL or upload'),

                Toggle::make('is_active')
                    ->label('Visible in Store')
                    ->default(true),

                Toggle::make('is_featured')
                    ->label('Featured Product')
                    ->default(false),

                Toggle::make('is_bestseller')
                    ->label('Mark as Bestseller')
                    ->default(false),

                Textarea::make('short_description')
                    ->columnSpanFull()
                    ->rows(3),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->rows(6)
                    ->helperText('Markdown formatted detailed description'),

                TextInput::make('meta_title')
                    ->columnSpanFull(),

                Textarea::make('meta_description')
                    ->columnSpanFull()
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&auto=format&fit=crop&q=80'),

                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Product $record): string => "SKU: {$record->sku}"),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('rating_average')
                    ->label('Rating')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('is_featured')
                    ->label('Featured Only'),
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
