<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Models\Review;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = 'Sales & Orders';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('customer_name')
                    ->required(),

                TextInput::make('customer_email')
                    ->email(),

                Select::make('rating')
                    ->options([
                        5 => '★★★★★ (5 Stars - Exceptional)',
                        4 => '★★★★☆ (4 Stars - Very Good)',
                        3 => '★★★☆☆ (3 Stars - Average)',
                        2 => '★★☆☆☆ (2 Stars - Below Average)',
                        1 => '★☆☆☆☆ (1 Star - Poor)',
                    ])
                    ->default(5)
                    ->required(),

                TextInput::make('title')
                    ->label('Review Headline'),

                Textarea::make('comment')
                    ->label('Full Review Text')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),

                Select::make('status')
                    ->options([
                        'approved' => 'Approved (Live on Store)',
                        'pending' => 'Pending Moderation',
                        'rejected' => 'Rejected',
                    ])
                    ->default('approved')
                    ->required(),

                Toggle::make('is_verified_purchase')
                    ->label('Verified Buyer Badge')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->weight('bold')
                    ->searchable()
                    ->limit(25),

                TextColumn::make('customer_name')
                    ->searchable(),

                TextColumn::make('rating')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        5 => 'success',
                        4 => 'info',
                        3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state)),

                TextColumn::make('title')
                    ->limit(30)
                    ->placeholder('No headline'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('is_verified_purchase')
                    ->label('Verified')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                        'rejected' => 'Rejected',
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
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
