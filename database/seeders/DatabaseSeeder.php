<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTracking;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Store Settings
        $settings = [
            'store_name' => ['value' => 'ZYRICZ', 'group' => 'general', 'type' => 'string'],
            'store_tagline' => ['value' => 'The Art of Modern Tech & Precision Living', 'group' => 'general', 'type' => 'string'],
            'store_email' => ['value' => 'concierge@zyricz.com', 'group' => 'general', 'type' => 'string'],
            'store_phone' => ['value' => '+1 (800) 997-4299', 'group' => 'general', 'type' => 'string'],
            'currency_symbol' => ['value' => '$', 'group' => 'localization', 'type' => 'string'],
            'currency_code' => ['value' => 'USD', 'group' => 'localization', 'type' => 'string'],
            'tax_rate' => ['value' => '8.5', 'group' => 'finance', 'type' => 'float'],
            'free_shipping_threshold' => ['value' => '150.00', 'group' => 'shipping', 'type' => 'float'],
            'standard_shipping_rate' => ['value' => '12.00', 'group' => 'shipping', 'type' => 'float'],
            'express_shipping_rate' => ['value' => '28.00', 'group' => 'shipping', 'type' => 'float'],
        ];

        foreach ($settings as $key => $data) {
            StoreSetting::set($key, $data['value'], $data['group'], $data['type']);
        }

        // 2. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@zyricz.com'],
            [
                'name' => 'Alexander Vance',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1 (555) 019-2834',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@zyricz.com'],
            [
                'name' => 'Elena Rostova',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+1 (555) 392-8172',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // Address for customer
        Address::firstOrCreate(
            ['user_id' => $customer->id, 'address_line_1' => '742 Evergreen Terrace'],
            [
                'type' => 'shipping',
                'first_name' => 'Elena',
                'last_name' => 'Rostova',
                'email' => 'customer@zyricz.com',
                'phone' => '+1 (555) 392-8172',
                'address_line_2' => 'Suite 400',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94107',
                'country' => 'United States',
                'is_default' => true,
            ]
        );

        // 3. Brands
        $brandsData = [
            [
                'name' => 'Zyricz Atelier',
                'slug' => 'zyricz-atelier',
                'description' => 'Flagship signature creations engineered with aerospace-grade materials and obsessive craftsmanship.',
                'is_featured' => true,
            ],
            [
                'name' => 'Vesper Acoustics',
                'slug' => 'vesper-acoustics',
                'description' => 'Nordic audiophile engineering delivering pure, unadulterated high-resolution soundstages.',
                'is_featured' => true,
            ],
            [
                'name' => 'Aethelgard Chrono',
                'slug' => 'aethelgard-chrono',
                'description' => 'Swiss-inspired mechanical horology for modern connoisseurs and visionaries.',
                'is_featured' => true,
            ],
            [
                'name' => 'Lumina Deskware',
                'slug' => 'lumina-deskware',
                'description' => 'Minimalist architecture designed to elevate focus and tactile workspace tranquility.',
                'is_featured' => true,
            ],
            [
                'name' => 'Komorebi Optics',
                'slug' => 'komorebi-optics',
                'description' => 'Precision Japanese glass and hand-finished titanium optical silhouettes.',
                'is_featured' => false,
            ],
        ];

        $brands = [];
        foreach ($brandsData as $b) {
            $brands[$b['slug']] = Brand::firstOrCreate(['slug' => $b['slug']], $b);
        }

        // 4. Categories
        $categoriesData = [
            [
                'name' => 'Audio & Acoustics',
                'slug' => 'audio-acoustics',
                'description' => 'Reference-grade wireless headphones, open-back monitors, and acoustic drivers.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'icon' => 'speaker-wave',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Horology & Timepieces',
                'slug' => 'luxury-timepieces',
                'description' => 'Automatic movements, sapphire crystal, and DLC-coated titanium cases.',
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
                'icon' => 'clock',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Leather & Minimal Carry',
                'slug' => 'leather-carry',
                'description' => 'Full-grain Italian vegetable-tanned leather folios, backpacks, and RFID cardholders.',
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
                'icon' => 'briefcase',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Smart Workspace',
                'slug' => 'smart-workspace',
                'description' => 'Anodized aluminum monitor bars, wireless MagSafe stands, and mechanical keyboard artisans.',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                'icon' => 'computer-desktop',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Precision Optics',
                'slug' => 'optics-vision',
                'description' => 'Polarized UV400 lenses housed in handcrafted acetate and titanium temples.',
                'image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&auto=format&fit=crop&q=80',
                'icon' => 'eye',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Everyday Essentials',
                'slug' => 'everyday-essentials',
                'description' => 'Precision-machined titanium pens, key organizers, and minimalist EDC gear.',
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80',
                'icon' => 'sparkles',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        // 5. Products Catalog
        $productsData = [
            [
                'name' => 'Zyricz Horizon Spatial Headphones',
                'slug' => 'zyricz-horizon-spatial-headphones',
                'sku' => 'ZYR-AUD-001',
                'brand_slug' => 'vesper-acoustics',
                'category_slug' => 'audio-acoustics',
                'short_description' => 'Flagship active noise-cancelling wireless headphones with 40mm beryllium drivers and beryllium diaphragms.',
                'description' => "Crafted from aircraft-grade aluminum, memory foam ear cups enveloped in lambskin leather, and engineered with dual DAC architecture. The Zyricz Horizon immerses you into an expansive 3D soundscape with near-zero latency wireless LDAC transmission.\n\n### Key Highlights\n- **Hybrid Active Noise Cancelling**: 4-microphone array cancels up to 42dB of environmental rumble.\n- **48-Hour Ultra Battery**: Quick charge delivers 6 hours of playtime in just 10 minutes.\n- **Bespoke Acoustics**: Tuned by world-renowned sound engineers for neutral transparency.",
                'price' => 399.00,
                'compare_at_price' => 479.00,
                'cost_price' => 190.00,
                'stock' => 28,
                'low_stock_threshold' => 6,
                'featured_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'rating_average' => 4.95,
                'reviews_count' => 18,
                'specifications' => [
                    'Driver Unit' => '40mm Custom Beryllium Acoustic',
                    'Frequency Response' => '5Hz - 45,000Hz',
                    'Weight' => '278 grams',
                    'Connectivity' => 'Bluetooth 5.3, USB-C Lossless, 3.5mm Aux',
                    'Battery Life' => '48 Hours (ANC On)',
                ],
                'variants' => [
                    ['name' => 'Space Black / Midnight', 'sku' => 'ZYR-AUD-001-BLK', 'price_modifier' => 0.00, 'stock' => 15],
                    ['name' => 'Titanium Silver / Chalk', 'sku' => 'ZYR-AUD-001-SLV', 'price_modifier' => 0.00, 'stock' => 13],
                ],
            ],
            [
                'name' => 'Aethelgard Chrono Phantom Automatic',
                'slug' => 'aethelgard-chrono-phantom-automatic',
                'sku' => 'ZYR-WTC-002',
                'brand_slug' => 'aethelgard-chrono',
                'category_slug' => 'luxury-timepieces',
                'short_description' => 'Open-heart skeleton movement watch with DLC matte black titanium case and double-domed sapphire.',
                'description' => "The Phantom Automatic is a celebration of mechanical purity. Designed for collectors who appreciate every moving gear, balance wheel, and hand-beveled bridge.\n\n### Horological Excellence\n- **Calibre 9015 Modified Movement**: 28,800 vibrations per hour with a 42-hour power reserve.\n- **Grade 5 Titanium**: 40mm case offering unbeatable tensile strength at half the weight of stainless steel.\n- **Water Resistance**: Tested to 10 ATM / 100 meters with screw-down crown.",
                'price' => 849.00,
                'compare_at_price' => 995.00,
                'cost_price' => 380.00,
                'stock' => 9,
                'low_stock_threshold' => 3,
                'featured_image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'rating_average' => 4.90,
                'reviews_count' => 12,
                'specifications' => [
                    'Case Diameter' => '40mm',
                    'Case Material' => 'Grade 5 Titanium DLC Coated',
                    'Crystal' => 'Anti-Reflective Sapphire Double Domed',
                    'Power Reserve' => '42 Hours',
                    'Strap' => 'FKM Fluororubber & Horween Leather',
                ],
                'variants' => [
                    ['name' => 'Obsidian DLC / Black Rubber', 'sku' => 'ZYR-WTC-002-OBS', 'price_modifier' => 0.00, 'stock' => 5],
                    ['name' => 'Raw Titanium / Tan Horween', 'sku' => 'ZYR-WTC-002-RAW', 'price_modifier' => 50.00, 'stock' => 4],
                ],
            ],
            [
                'name' => 'Lumina Apex MagSafe Desk Hub',
                'slug' => 'lumina-apex-magsafe-desk-hub',
                'sku' => 'ZYR-DSK-003',
                'brand_slug' => 'lumina-deskware',
                'category_slug' => 'smart-workspace',
                'short_description' => 'Precision CNC machined aluminum 3-in-1 fast charger with weighted base and ambient light bar.',
                'description' => "Transform your desk into an organized architectural sanctuary. The Apex MagSafe Hub charges your phone, smartwatch, and audio buds simultaneously with certified 15W Qi2 wireless architecture.",
                'price' => 165.00,
                'compare_at_price' => 195.00,
                'cost_price' => 70.00,
                'stock' => 45,
                'low_stock_threshold' => 10,
                'featured_image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1588508065123-287b28e013da?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'rating_average' => 4.80,
                'reviews_count' => 9,
                'specifications' => [
                    'Material' => 'Solid Anodized 6063 Aluminum',
                    'Total Output' => '30W Max (15W Qi2 + 5W Watch + 5W Pods)',
                    'Cable' => '2.0m Braided Kevlar Type-C included',
                ],
            ],
            [
                'name' => 'Vesper Studio Reference Pro Monitors',
                'slug' => 'vesper-studio-reference-pro-monitors',
                'sku' => 'ZYR-AUD-004',
                'brand_slug' => 'vesper-acoustics',
                'category_slug' => 'audio-acoustics',
                'short_description' => 'Audiophile desktop stereo bookshelf monitors with planar ribbon tweeters and walnut enclosure.',
                'description' => "Acoustic perfection meets Scandinavian minimalism. Hand-selected solid walnut timber enclosures housing 5.25-inch composite woofers and ribbon tweeters for silky highs and deep sub-bass articulation.",
                'price' => 590.00,
                'compare_at_price' => 680.00,
                'cost_price' => 280.00,
                'stock' => 14,
                'low_stock_threshold' => 4,
                'featured_image' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'rating_average' => 4.88,
                'reviews_count' => 7,
                'specifications' => [
                    'Amplifier Power' => '120W RMS Class-D',
                    'Inputs' => 'Bluetooth 5.3 AptX HD, Optical, RCA, USB Audio',
                    'Finish' => 'Solid American Walnut & Matte Black Baffle',
                ],
            ],
            [
                'name' => 'Zyricz Sovereign Leather Commuter Folio',
                'slug' => 'zyricz-sovereign-leather-commuter-folio',
                'sku' => 'ZYR-LTH-005',
                'brand_slug' => 'zyricz-atelier',
                'category_slug' => 'leather-carry',
                'short_description' => 'Full-grain vegetable tanned Italian leather briefcase with magnetic fidlock clasps and dedicated 16-inch laptop chamber.',
                'description' => "Hand-stitched in Tuscany using certified vegetable-tanned hides that develop a rich, luminous patina over decades of journeying. Features internal organizer pockets for tech gear, passport, and pens.",
                'price' => 445.00,
                'compare_at_price' => 520.00,
                'cost_price' => 210.00,
                'stock' => 18,
                'low_stock_threshold' => 5,
                'featured_image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'rating_average' => 5.00,
                'reviews_count' => 14,
                'specifications' => [
                    'Leather Origin' => 'Santa Croce sull’Arno, Italy',
                    'Hardware' => 'German YKK Excella & Fidlock Magnets',
                    'Dimensions' => '40 x 30 x 9 cm (Fits 16 MacBook Pro)',
                ],
                'variants' => [
                    ['name' => 'Espresso Brown', 'sku' => 'ZYR-LTH-005-ESP', 'price_modifier' => 0.00, 'stock' => 10],
                    ['name' => 'Raven Black', 'sku' => 'ZYR-LTH-005-BLK', 'price_modifier' => 0.00, 'stock' => 8],
                ],
            ],
            [
                'name' => 'Komorebi Mono Titan Sunglasses',
                'slug' => 'komorebi-mono-titan-sunglasses',
                'sku' => 'ZYR-OPT-006',
                'brand_slug' => 'komorebi-optics',
                'category_slug' => 'optics-vision',
                'short_description' => 'Ultra-lightweight Japanese beta-titanium aviator sunglasses with mineral glass polarized lenses.',
                'description' => "Weighing only 18 grams, the Mono Titan combines classical geometric silhouettes with military-grade Japanese beta-titanium flexibility and 100% anti-glare oleophobic lens coatings.",
                'price' => 285.00,
                'compare_at_price' => 340.00,
                'cost_price' => 110.00,
                'stock' => 22,
                'low_stock_threshold' => 5,
                'featured_image' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => true,
                'rating_average' => 4.75,
                'reviews_count' => 8,
                'specifications' => [
                    'Frame' => 'Japanese Beta-Titanium',
                    'Lenses' => 'Barberini Mineral Glass UV400 Polarized',
                    'Weight' => '18 grams',
                ],
            ],
            [
                'name' => 'Zyricz Tactile 75% Mechanical Keyboard',
                'slug' => 'zyricz-tactile-mechanical-keyboard',
                'sku' => 'ZYR-KBD-007',
                'brand_slug' => 'zyricz-atelier',
                'category_slug' => 'smart-workspace',
                'short_description' => 'Gasket-mounted aluminum keyboard with hot-swappable lubed switches and wireless tri-mode connectivity.',
                'description' => "Milled from a single 2.1kg block of 6063 aerospace aluminum, the Zyricz Tactile features poron dampening sheets, factory-lubed linear switches, and dye-sub PBT keycaps with custom sound signatures.",
                'price' => 275.00,
                'compare_at_price' => 310.00,
                'cost_price' => 125.00,
                'stock' => 31,
                'low_stock_threshold' => 8,
                'featured_image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'rating_average' => 4.92,
                'reviews_count' => 11,
                'specifications' => [
                    'Mounting' => 'Double Gasket Leaf-Spring System',
                    'Weight' => '2.1 kg (4.6 lbs)',
                    'Switches' => 'Zyricz Moonstone Linear (Pre-lubed Krytox 205g0)',
                    'Battery' => '4000mAh Lithium Polymer',
                ],
            ],
            [
                'name' => 'Zyricz Minimalist RFID Cardholder',
                'slug' => 'zyricz-minimalist-rfid-cardholder',
                'sku' => 'ZYR-LTH-008',
                'brand_slug' => 'zyricz-atelier',
                'category_slug' => 'leather-carry',
                'short_description' => 'Ultra-slim aerospace carbon fiber and bridle leather cardholder with instant eject button.',
                'description' => "Holds up to 7 embossed cards with quick ergonomic thumb access and RFID shielding against electronic theft. Wrapped in full-grain French bridle leather.",
                'price' => 89.00,
                'compare_at_price' => 110.00,
                'cost_price' => 32.00,
                'stock' => 54,
                'low_stock_threshold' => 12,
                'featured_image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => true,
                'rating_average' => 4.85,
                'reviews_count' => 24,
                'specifications' => [
                    'Capacity' => '6-8 Cards + Folded Cash',
                    'Material' => 'Forged Carbon Fiber & Saddle Leather',
                    'Thickness' => '8.5 mm',
                ],
            ],
            [
                'name' => 'Lumina Horizon ScreenBar Halo',
                'slug' => 'lumina-horizon-screenbar-halo',
                'sku' => 'ZYR-DSK-009',
                'brand_slug' => 'lumina-deskware',
                'category_slug' => 'smart-workspace',
                'short_description' => 'Asymmetric optical monitor light bar with wireless dial controller and back ambient glow.',
                'description' => "Zero glare, zero screen reflection. Intelligently illuminates your desktop workspace with CRI>97 natural color rendering and step-less color temperature calibration from 2700K to 6500K.",
                'price' => 179.00,
                'compare_at_price' => 210.00,
                'cost_price' => 75.00,
                'stock' => 26,
                'low_stock_threshold' => 5,
                'featured_image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => false,
                'rating_average' => 4.88,
                'reviews_count' => 15,
                'specifications' => [
                    'CRI' => 'Ra > 97 Ultra Precision',
                    'Control' => '2.4GHz Wireless Precision Wheel Controller',
                    'Power' => 'USB-C 5V/2A',
                ],
            ],
            [
                'name' => 'Aethelgard Terra Explorer Field Watch',
                'slug' => 'aethelgard-terra-explorer-field-watch',
                'sku' => 'ZYR-WTC-010',
                'brand_slug' => 'aethelgard-chrono',
                'category_slug' => 'luxury-timepieces',
                'short_description' => 'Rugged automatic field watch with compass bezel, tritium gas tubes, and 200m water resistance.',
                'description' => "Built to conquer harsh elements while retaining black-tie subtlety. Equipped with micro-tritium gas vials on every index that illuminate continuously for 25 years without requiring sunlight exposure.",
                'price' => 620.00,
                'compare_at_price' => 720.00,
                'cost_price' => 270.00,
                'stock' => 11,
                'low_stock_threshold' => 3,
                'featured_image' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'rating_average' => 4.95,
                'reviews_count' => 6,
                'specifications' => [
                    'Movement' => 'Sellita SW200-1 Automatic Swiss Made',
                    'Lume' => 'T25 Micro Tritium Tubes',
                    'Water Resistance' => '20 ATM (200m)',
                ],
            ],
            [
                'name' => 'Zyricz Titanium Bolt-Action Pen',
                'slug' => 'zyricz-titanium-bolt-action-pen',
                'sku' => 'ZYR-EDC-011',
                'brand_slug' => 'zyricz-atelier',
                'category_slug' => 'everyday-essentials',
                'short_description' => 'Solid Grade 5 titanium writing instrument with smooth fluid bolt mechanism and Schmidt rollerball refill.',
                'description' => "Machined with micron-level tolerances from solid bar-stock titanium. The bolt action mechanism delivers an irresistible tactile click that makes handwriting an everyday pleasure.",
                'price' => 115.00,
                'compare_at_price' => 135.00,
                'cost_price' => 40.00,
                'stock' => 40,
                'low_stock_threshold' => 10,
                'featured_image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => true,
                'rating_average' => 4.90,
                'reviews_count' => 16,
                'specifications' => [
                    'Material' => 'Grade 5 Titanium (Ti-6Al-4V)',
                    'Refill' => 'Schmidt EasyFlow 9000M / Parker Standard G2',
                    'Weight' => '34 grams',
                ],
            ],
            [
                'name' => 'Vesper Pure True Wireless Earbuds',
                'slug' => 'vesper-pure-true-wireless-earbuds',
                'sku' => 'ZYR-AUD-012',
                'brand_slug' => 'vesper-acoustics',
                'category_slug' => 'audio-acoustics',
                'short_description' => 'Audiophile in-ear monitors with planar magnetic drivers and wireless charging pebble case.',
                'description' => "Studio purity in the palm of your hand. Delivers crystal clarity across vocals and instrument separation without digital over-processing.",
                'price' => 249.00,
                'compare_at_price' => 299.00,
                'cost_price' => 95.00,
                'stock' => 35,
                'low_stock_threshold' => 8,
                'featured_image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'rating_average' => 4.82,
                'reviews_count' => 13,
                'specifications' => [
                    'Driver' => '10mm Carbon Nanotube Dynamic Driver',
                    'Playtime' => '8 Hours (+28 Hours in Pebble Case)',
                    'Water Resistance' => 'IPX5 Splash Resistant',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $brandSlug = $pData['brand_slug'];
            $categorySlug = $pData['category_slug'];
            $variants = $pData['variants'] ?? [];

            unset($pData['brand_slug'], $pData['category_slug'], $pData['variants']);

            $pData['brand_id'] = $brands[$brandSlug]->id ?? null;

            $product = Product::firstOrCreate(['slug' => $pData['slug']], $pData);

            if (isset($categories[$categorySlug])) {
                $product->categories()->syncWithoutDetaching([$categories[$categorySlug]->id]);
            }

            // Variants
            foreach ($variants as $var) {
                ProductVariant::firstOrCreate(['sku' => $var['sku']], array_merge($var, ['product_id' => $product->id]));
            }
        }

        // 6. Seed Coupons
        $couponsData = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_order_amount' => 50.00,
                'max_discount_amount' => 100.00,
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'ZYRICZ25',
                'type' => 'fixed',
                'value' => 25.00,
                'min_order_amount' => 150.00,
                'max_discount_amount' => 25.00,
                'usage_limit' => 200,
                'is_active' => true,
            ],
            [
                'code' => 'VIPLUXURY',
                'type' => 'percentage',
                'value' => 15.00,
                'min_order_amount' => 300.00,
                'max_discount_amount' => 250.00,
                'usage_limit' => 100,
                'is_active' => true,
            ],
        ];

        foreach ($couponsData as $cp) {
            Coupon::firstOrCreate(['code' => $cp['code']], $cp);
        }

        // 7. Seed Reviews
        $topProduct = Product::where('slug', 'zyricz-horizon-spatial-headphones')->first();
        if ($topProduct) {
            Review::firstOrCreate(
                ['product_id' => $topProduct->id, 'customer_email' => 'marcus.k@audiophile.io'],
                [
                    'customer_name' => 'Marcus K.',
                    'rating' => 5,
                    'title' => 'The absolute finest ANC headphones I have ever worn.',
                    'comment' => 'The acoustic soundstage is extraordinarily wide. The build quality feels like a luxury Swiss watch. The lambskin cups are cloud-soft even during transatlantic flights.',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                ]
            );

            Review::firstOrCreate(
                ['product_id' => $topProduct->id, 'customer_email' => 'sarah.j@designdaily.com'],
                [
                    'customer_name' => 'Sarah Jenkins',
                    'rating' => 5,
                    'title' => 'Pure craftsmanship and acoustic precision.',
                    'comment' => 'Clean minimalism without tacky branding. Battery life is stellar. Highly recommended for creative pros.',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                ]
            );
        }

        $watchProduct = Product::where('slug', 'aethelgard-chrono-phantom-automatic')->first();
        if ($watchProduct) {
            Review::firstOrCreate(
                ['product_id' => $watchProduct->id, 'customer_email' => 'david.sterling@investor.com'],
                [
                    'customer_name' => 'David Sterling',
                    'rating' => 5,
                    'title' => 'Skeletonized perfection on titanium.',
                    'comment' => 'The feather-light titanium case paired with the open-heart movement is mesmerizing to inspect in daylight. Stays accurate within +3s per day.',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                ]
            );
        }

        // 8. Seed Pre-Existing Orders (for rich admin dashboard metrics & customer order history)
        if ($topProduct && $customer) {
            // Completed Delivered Order
            $order1 = Order::firstOrCreate(
                ['order_number' => 'ZYR-2026-88412'],
                [
                    'user_id' => $customer->id,
                    'customer_name' => 'Elena Rostova',
                    'customer_email' => 'customer@zyricz.com',
                    'customer_phone' => '+1 (555) 392-8172',
                    'status' => 'delivered',
                    'payment_status' => 'paid',
                    'payment_method' => 'card',
                    'payment_reference' => 'ch_3N8eB72eZvKYlo2C1',
                    'shipping_method' => 'express',
                    'shipping_rate' => 28.00,
                    'subtotal' => 399.00,
                    'discount_amount' => 25.00,
                    'coupon_code' => 'ZYRICZ25',
                    'tax_amount' => 34.17,
                    'total' => 436.17,
                    'shipping_address' => [
                        'first_name' => 'Elena',
                        'last_name' => 'Rostova',
                        'address_line_1' => '742 Evergreen Terrace',
                        'city' => 'San Francisco',
                        'state' => 'CA',
                        'postal_code' => '94107',
                        'country' => 'United States',
                    ],
                    'tracking_number' => 'ZYR-FDX-99281',
                    'carrier' => 'FedEx Priority',
                    'estimated_delivery' => now()->subDays(2),
                    'customer_notes' => 'Please leave with front concierge desk.',
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order1->id, 'product_id' => $topProduct->id],
                [
                    'product_name' => $topProduct->name,
                    'product_sku' => $topProduct->sku,
                    'product_image' => $topProduct->featured_image,
                    'unit_price' => 399.00,
                    'quantity' => 1,
                    'subtotal' => 399.00,
                ]
            );

            $order1->trackings()->createMany([
                ['status_title' => 'Order Placed & Verified', 'location' => 'San Francisco, CA', 'description' => 'Payment authorized via Stripe Secure Gateway.', 'occurred_at' => now()->subDays(5)],
                ['status_title' => 'Handcrafted & Packed', 'location' => 'Zyricz Logistics Hub, Reno NV', 'description' => 'Packaged with tamper-evident premium seal.', 'occurred_at' => now()->subDays(4)],
                ['status_title' => 'Dispatched with FedEx Priority', 'location' => 'Reno NV Sorting Center', 'description' => 'In transit to destination facility.', 'occurred_at' => now()->subDays(3)],
                ['status_title' => 'Out for Delivery', 'location' => 'San Francisco Bay Distribution', 'description' => 'On vehicle for final delivery.', 'occurred_at' => now()->subDays(2)],
                ['status_title' => 'Delivered to Concierge', 'location' => 'San Francisco, CA', 'description' => 'Signed by Building Concierge.', 'occurred_at' => now()->subDays(2)->addHours(4)],
            ]);

            // Second Order: In Transit / Shipped
            $folioProduct = Product::where('slug', 'zyricz-sovereign-leather-commuter-folio')->first();
            if ($folioProduct) {
                $order2 = Order::firstOrCreate(
                    ['order_number' => 'ZYR-2026-91044'],
                    [
                        'user_id' => $customer->id,
                        'customer_name' => 'Elena Rostova',
                        'customer_email' => 'customer@zyricz.com',
                        'customer_phone' => '+1 (555) 392-8172',
                        'status' => 'shipped',
                        'payment_status' => 'paid',
                        'payment_method' => 'card',
                        'payment_reference' => 'ch_3N8eB72eZvKYlo9A4',
                        'shipping_method' => 'standard',
                        'shipping_rate' => 0.00,
                        'subtotal' => 445.00,
                        'discount_amount' => 44.50,
                        'coupon_code' => 'WELCOME10',
                        'tax_amount' => 34.04,
                        'total' => 434.54,
                        'shipping_address' => [
                            'first_name' => 'Elena',
                            'last_name' => 'Rostova',
                            'address_line_1' => '742 Evergreen Terrace',
                            'city' => 'San Francisco',
                            'state' => 'CA',
                            'postal_code' => '94107',
                            'country' => 'United States',
                        ],
                        'tracking_number' => 'ZYR-DHL-77142',
                        'carrier' => 'DHL Express',
                        'estimated_delivery' => now()->addDays(2),
                    ]
                );

                OrderItem::firstOrCreate(
                    ['order_id' => $order2->id, 'product_id' => $folioProduct->id],
                    [
                        'product_name' => $folioProduct->name,
                        'product_sku' => $folioProduct->sku,
                        'product_image' => $folioProduct->featured_image,
                        'unit_price' => 445.00,
                        'quantity' => 1,
                        'subtotal' => 445.00,
                    ]
                );

                $order2->trackings()->createMany([
                    ['status_title' => 'Order Placed & Confirmed', 'location' => 'San Francisco, CA', 'description' => 'Payment confirmed.', 'occurred_at' => now()->subDay()],
                    ['status_title' => 'Dispatched with DHL Express', 'location' => 'Hub West, Los Angeles', 'description' => 'Departed transit facility.', 'occurred_at' => now()->subHours(6)],
                ]);
            }
        }
    }
}
