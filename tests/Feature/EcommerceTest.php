<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\WishlistService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_home_page_loads_with_curated_catalog(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('ZYRICZ');
        $response->assertSee('Modern Precision');
    }

    public function test_catalog_search_and_category_filtering(): void
    {
        $searchResponse = $this->get('/shop?search=Headphones');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Spatial Headphones');

        $categoryResponse = $this->get('/shop?category=horology');
        $categoryResponse->assertStatus(200);
        $categoryResponse->assertSee('Chrono');
    }

    public function test_product_detail_page_loads_with_variants_and_specifications(): void
    {
        $product = Product::where('slug', 'zyricz-horizon-spatial-headphones')->firstOrFail();

        $response = $this->get('/products/' . $product->slug);

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Space Black / Midnight');
        $response->assertSee('Beryllium');
    }

    public function test_cart_management_and_coupon_application(): void
    {
        $product = Product::where('slug', 'zyricz-minimalist-rfid-cardholder')->firstOrFail();

        $cartService = app(CartService::class);
        $cartService->clear();

        // 1. Add item to cart
        $cartService->addItem($product->id, 2);
        $this->assertEquals(2, $cartService->count());

        // 2. Apply valid coupon WELCOME10 (10% discount)
        $couponResult = $cartService->applyCoupon('WELCOME10');
        $this->assertTrue($couponResult['success']);

        $summary = $cartService->getSummary();
        $this->assertNotNull($summary['coupon']);
        $this->assertGreaterThan(0, $summary['discount']);

        // 3. Remove item from cart
        $cartService->clear();
        $this->assertEquals(0, $cartService->count());
    }

    public function test_wishlist_toggle_workflow(): void
    {
        $product = Product::firstOrFail();
        $wishlistService = app(WishlistService::class);

        // Guest toggle add
        $toggleAdd = $wishlistService->toggle($product->id);
        $this->assertTrue($toggleAdd['added']);
        $this->assertTrue($wishlistService->has($product->id));

        // Guest toggle remove
        $toggleRemove = $wishlistService->toggle($product->id);
        $this->assertFalse($toggleRemove['added']);
        $this->assertFalse($wishlistService->has($product->id));
    }

    public function test_atomic_checkout_order_creation_and_inventory_decrement(): void
    {
        $product = Product::where('slug', 'zyricz-titanium-bolt-action-pen')->firstOrFail();
        $initialStock = $product->stock;

        $cartService = app(CartService::class);
        $cartService->clear();
        $cartService->addItem($product->id, 1);

        $checkoutService = app(CheckoutService::class);

        $checkoutData = [
            'first_name' => 'Alexander',
            'last_name' => 'Vane',
            'email' => 'alexander.vane@atelier-luxury.com',
            'phone' => '+1 (555) 492-8810',
            'address_line_1' => '740 Park Avenue, Penthouse 12',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10021',
            'country' => 'United States',
            'shipping_method' => 'express',
            'payment_method' => 'card',
            'customer_notes' => 'Please request signature upon concierge delivery.',
        ];

        $result = $checkoutService->processOrder($checkoutData);

        $this->assertTrue($result['success']);
        $order = $result['order'];

        $this->assertInstanceOf(Order::class, $order);
        $this->assertStringStartsWith('ZYR-', $order->order_number);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->status);

        // Check stock decrement
        $product->refresh();
        $this->assertEquals($initialStock - 1, $product->stock);

        // Verify order tracking events were automatically initiated
        $this->assertGreaterThanOrEqual(1, $order->trackings()->count());
        $this->assertTrue($order->trackings->pluck('status_title')->contains('Order Confirmed'));
    }

    public function test_order_tracking_lookup(): void
    {
        $response = $this->get('/track-order?order_number=ZYR-2026-91044');

        $response->assertStatus(200);
        $response->assertSee('ZYR-2026-91044');
        $response->assertSee('Shipped');
    }

    public function test_filament_admin_panel_access_control(): void
    {
        // 1. Unauthenticated redirect to login
        $guestResponse = $this->get('/admin');
        $guestResponse->assertRedirect('/admin/login');

        // 2. Regular customer without admin flag cannot access
        $customer = User::where('email', 'customer@zyricz.com')->firstOrFail();
        $customerResponse = $this->actingAs($customer)->get('/admin');
        $customerResponse->assertStatus(403);

        // 3. Admin user with is_admin = true can access Filament panel
        $admin = User::where('email', 'admin@zyricz.com')->firstOrFail();
        $adminResponse = $this->actingAs($admin)->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('ZYRICZ');
        $adminResponse->assertSee('Commerce Control');
    }
}
