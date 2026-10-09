<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isValidForAmount(float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is no longer active.'];
        }

        if ($this->starts_at && Carbon::now()->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'Coupon is not yet active.'];
        }

        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached.'];
        }

        if ($this->min_order_amount !== null && $subtotal < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount for this coupon is $' . number_format($this->min_order_amount, 2),
            ];
        }

        return ['valid' => true, 'message' => 'Coupon applied successfully!'];
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * (float) $this->value) / 100;
        } else {
            $discount = (float) $this->value;
        }

        if ($this->max_discount_amount !== null && $discount > $this->max_discount_amount) {
            $discount = (float) $this->max_discount_amount;
        }

        return min($discount, $subtotal);
    }
}
