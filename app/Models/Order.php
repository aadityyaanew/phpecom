<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'shipping_method',
        'shipping_rate',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'tax_amount',
        'total',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'carrier',
        'estimated_delivery',
        'customer_notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'shipping_rate' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'estimated_delivery' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        return 'ZYR-' . date('Y') . '-' . strtoupper(Str::random(6));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(OrderTracking::class)->orderBy('occurred_at', 'desc');
    }

    public function addTracking(string $title, ?string $location = null, ?string $description = null): OrderTracking
    {
        return $this->trackings()->create([
            'status_title' => $title,
            'location' => $location,
            'description' => $description,
            'occurred_at' => now(),
        ]);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped(Builder $query): Builder
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', 'delivered');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    // Helpers
    public function getFormattedStatusAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Confirmation',
            'processing' => 'Processing in Warehouse',
            'shipped' => 'Shipped / In Transit',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'emerald',
            'cancelled' => 'rose',
            default => 'slate',
        };
    }
}
