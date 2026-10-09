<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price_modifier',
        'stock',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
            'stock' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getCalculatedPriceAttribute(): float
    {
        return (float) ($this->product->price + $this->price_modifier);
    }
}
