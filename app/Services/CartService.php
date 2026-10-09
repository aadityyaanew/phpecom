<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'zyricz_cart';
    protected const COUPON_KEY = 'zyricz_cart_coupon';
    protected const SHIPPING_KEY = 'zyricz_shipping_method';

    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return (int) array_sum(array_column($this->getItems(), 'quantity'));
    }

    public function isEmpty(): bool
    {
        return empty($this->getItems());
    }

    public function addItem(int $productId, int $quantity = 1, ?int $variantId = null): array
    {
        $product = Product::active()->find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found or unavailable.'];
        }

        $variant = $variantId ? ProductVariant::where('product_id', $productId)->find($variantId) : null;
        $unitPrice = $variant ? $variant->calculated_price : (float) $product->price;
        $availableStock = $variant ? $variant->stock : $product->stock;

        $cart = $this->getItems();
        $itemKey = $variantId ? "{$productId}_{$variantId}" : (string) $productId;

        $currentQty = isset($cart[$itemKey]) ? $cart[$itemKey]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $availableStock) {
            return [
                'success' => false,
                'message' => "Only {$availableStock} units available in stock.",
            ];
        }

        $cart[$itemKey] = [
            'key' => $itemKey,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'name' => $product->name,
            'variant_name' => $variant?->name,
            'slug' => $product->slug,
            'sku' => $variant ? $variant->sku : $product->sku,
            'image' => $product->primary_image,
            'price' => $unitPrice,
            'quantity' => $newQty,
            'total' => round($unitPrice * $newQty, 2),
        ];

        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => "Added '{$product->name}' to bag.",
            'cart' => $this->getSummary(),
        ];
    }

    public function updateItem(string $itemKey, int $quantity): array
    {
        $cart = $this->getItems();
        if (!isset($cart[$itemKey])) {
            return ['success' => false, 'message' => 'Item not in cart.'];
        }

        if ($quantity <= 0) {
            return $this->removeItem($itemKey);
        }

        $item = $cart[$itemKey];
        $product = Product::find($item['product_id']);
        $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;
        $maxStock = $variant ? $variant->stock : ($product?->stock ?? 0);

        if ($quantity > $maxStock) {
            return [
                'success' => false,
                'message' => "Maximum available stock is {$maxStock} units.",
            ];
        }

        $cart[$itemKey]['quantity'] = $quantity;
        $cart[$itemKey]['total'] = round($cart[$itemKey]['price'] * $quantity, 2);

        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => 'Cart updated.',
            'cart' => $this->getSummary(),
        ];
    }

    public function removeItem(string $itemKey): array
    {
        $cart = $this->getItems();
        if (isset($cart[$itemKey])) {
            $name = $cart[$itemKey]['name'];
            unset($cart[$itemKey]);
            Session::put(self::SESSION_KEY, $cart);

            return [
                'success' => true,
                'message' => "Removed '{$name}' from bag.",
                'cart' => $this->getSummary(),
            ];
        }

        return ['success' => false, 'message' => 'Item not found.'];
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::COUPON_KEY);
    }

    public function applyCoupon(string $code): array
    {
        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid promotional coupon code.'];
        }

        $subtotal = $this->getSubtotal();
        $validation = $coupon->isValidForAmount($subtotal);

        if (!$validation['valid']) {
            return ['success' => false, 'message' => $validation['message']];
        }

        Session::put(self::COUPON_KEY, $coupon->code);

        return [
            'success' => true,
            'message' => "Coupon '{$coupon->code}' applied successfully!",
            'cart' => $this->getSummary(),
        ];
    }

    public function removeCoupon(): array
    {
        Session::forget(self::COUPON_KEY);

        return [
            'success' => true,
            'message' => 'Promotional code removed.',
            'cart' => $this->getSummary(),
        ];
    }

    public function setShippingMethod(string $method): void
    {
        Session::put(self::SHIPPING_KEY, $method);
    }

    public function getShippingMethod(): string
    {
        return Session::get(self::SHIPPING_KEY, 'standard');
    }

    public function getSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->getItems() as $item) {
            $subtotal += $item['total'];
        }

        return round($subtotal, 2);
    }

    public function getAppliedCoupon(): ?Coupon
    {
        $code = Session::get(self::COUPON_KEY);
        if (!$code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();
        if ($coupon) {
            $validation = $coupon->isValidForAmount($this->getSubtotal());
            if ($validation['valid']) {
                return $coupon;
            }
            Session::forget(self::COUPON_KEY);
        }

        return null;
    }

    public function getDiscountAmount(): float
    {
        $coupon = $this->getAppliedCoupon();
        if (!$coupon) {
            return 0.00;
        }

        return round($coupon->calculateDiscount($this->getSubtotal()), 2);
    }

    public function getShippingFee(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.00;
        }

        $method = $this->getShippingMethod();
        $freeShippingThreshold = (float) StoreSetting::get('free_shipping_threshold', 150.00);

        if ($method === 'express') {
            return (float) StoreSetting::get('express_shipping_rate', 28.00);
        }

        if ($method === 'priority') {
            return 45.00;
        }

        // Standard shipping
        if ($subtotal >= $freeShippingThreshold) {
            return 0.00;
        }

        return (float) StoreSetting::get('standard_shipping_rate', 12.00);
    }

    public function getTaxAmount(): float
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscountAmount();
        $taxableAmount = max(0, $subtotal - $discount);
        $taxRate = (float) StoreSetting::get('tax_rate', 8.5) / 100;

        return round($taxableAmount * $taxRate, 2);
    }

    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        if ($subtotal <= 0) {
            return 0.00;
        }

        $discount = $this->getDiscountAmount();
        $shipping = $this->getShippingFee();
        $tax = $this->getTaxAmount();

        return round(max(0, $subtotal - $discount + $shipping + $tax), 2);
    }

    public function getSummary(): array
    {
        $subtotal = $this->getSubtotal();
        $items = $this->getItems();
        $count = array_sum(array_column($items, 'quantity'));
        $freeShippingThreshold = (float) StoreSetting::get('free_shipping_threshold', 150.00);
        $amountForFreeShipping = max(0, $freeShippingThreshold - $subtotal);
        $freeShippingProgress = min(100, (int) round(($subtotal / $freeShippingThreshold) * 100));

        return [
            'items' => array_values($items),
            'items_count' => $count,
            'subtotal' => $subtotal,
            'discount' => $this->getDiscountAmount(),
            'coupon' => $this->getAppliedCoupon(),
            'shipping' => $this->getShippingFee(),
            'shipping_method' => $this->getShippingMethod(),
            'tax' => $this->getTaxAmount(),
            'total' => $this->getTotal(),
            'free_shipping_threshold' => $freeShippingThreshold,
            'amount_for_free_shipping' => round($amountForFreeShipping, 2),
            'free_shipping_progress' => $freeShippingProgress,
            'qualifies_for_free_shipping' => $subtotal >= $freeShippingThreshold,
        ];
    }
}
