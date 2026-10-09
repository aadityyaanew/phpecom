<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        protected CartService $cart
    ) {}

    public function processOrder(array $validatedData): array
    {
        $items = $this->cart->getItems();
        if (empty($items)) {
            return [
                'success' => false,
                'message' => 'Your shopping bag is empty.',
            ];
        }

        $summary = $this->cart->getSummary();
        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($validatedData, $items, $summary, $user) {
                // 1. Verify stock availability
                foreach ($items as $item) {
                    $product = Product::lockForUpdate()->find($item['product_id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for item: {$item['name']}");
                    }

                    if (!empty($item['variant_id'])) {
                        $variant = ProductVariant::lockForUpdate()->find($item['variant_id']);
                        if (!$variant || $variant->stock < $item['quantity']) {
                            throw new \Exception("Insufficient stock for variant: {$item['variant_name']}");
                        }
                    }
                }

                // 2. Prepare Payment Reference
                $paymentMethod = $validatedData['payment_method'];
                $paymentReference = match ($paymentMethod) {
                    'card' => 'ch_zyr_' . Str::random(24),
                    'paypal' => 'pp_tx_' . Str::random(20),
                    'cod' => 'cod_pending_' . Str::random(12),
                    default => 'trx_' . Str::random(16),
                };

                $paymentStatus = in_array($paymentMethod, ['card', 'paypal']) ? 'paid' : 'pending';

                // Shipping address payload
                $shippingAddress = [
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'] ?? null,
                    'address_line_1' => $validatedData['address_line_1'],
                    'address_line_2' => $validatedData['address_line_2'] ?? null,
                    'city' => $validatedData['city'],
                    'state' => $validatedData['state'] ?? null,
                    'postal_code' => $validatedData['postal_code'],
                    'country' => $validatedData['country'] ?? 'United States',
                ];

                // 3. Create Order Record
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user?->id,
                    'customer_name' => "{$validatedData['first_name']} {$validatedData['last_name']}",
                    'customer_email' => $validatedData['email'],
                    'customer_phone' => $validatedData['phone'] ?? null,
                    'status' => 'processing',
                    'payment_status' => $paymentStatus,
                    'payment_method' => $paymentMethod,
                    'payment_reference' => $paymentReference,
                    'shipping_method' => $summary['shipping_method'],
                    'shipping_rate' => $summary['shipping'],
                    'subtotal' => $summary['subtotal'],
                    'discount_amount' => $summary['discount'],
                    'coupon_code' => $summary['coupon']?->code,
                    'tax_amount' => $summary['tax'],
                    'total' => $summary['total'],
                    'shipping_address' => $shippingAddress,
                    'billing_address' => !empty($validatedData['same_as_shipping']) ? $shippingAddress : ($validatedData['billing_address'] ?? $shippingAddress),
                    'carrier' => $summary['shipping_method'] === 'express' ? 'DHL Express Priority' : 'FedEx Home Delivery',
                    'tracking_number' => 'ZYR-' . strtoupper(Str::random(10)),
                    'estimated_delivery' => now()->addDays($summary['shipping_method'] === 'express' ? 2 : 5),
                    'customer_notes' => $validatedData['notes'] ?? null,
                ]);

                // 4. Create Order Items & Decrement Stock
                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['variant_id'] ?? null,
                        'product_name' => $item['name'],
                        'product_sku' => $item['sku'],
                        'product_image' => $item['image'],
                        'variant_details' => $item['variant_name'] ? ['variant' => $item['variant_name']] : null,
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['total'],
                    ]);

                    // Deduct stock
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                    if (!empty($item['variant_id'])) {
                        ProductVariant::where('id', $item['variant_id'])->decrement('stock', $item['quantity']);
                    }
                }

                // 5. Increment coupon usage if used
                if ($summary['coupon']) {
                    $summary['coupon']->increment('used_count');
                }

                // 6. Add Initial Tracking Point
                $order->trackings()->createMany([
                    [
                        'status_title' => 'Order Confirmed',
                        'location' => 'Zyricz Atelier Hub',
                        'description' => $paymentStatus === 'paid' ? 'Payment verified and authorized.' : 'Order confirmed; payment due upon receipt.',
                        'occurred_at' => now(),
                    ],
                    [
                        'status_title' => 'Processing at Fulfillment Atelier',
                        'location' => 'San Francisco, CA',
                        'description' => 'Items retrieved and undergoing artisan quality verification.',
                        'occurred_at' => now()->addMinutes(2),
                    ],
                ]);

                return $order;
            });

            // Clear cart
            $this->cart->clear();

            return [
                'success' => true,
                'order' => $order,
                'message' => 'Order placed successfully!',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
