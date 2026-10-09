@php
    $images = $product->all_images;
    $variants = $product->variants;
    $hasVariants = $variants->isNotEmpty();
@endphp

<x-layout :title="$product->name" :meta-description="$product->short_description" :og-image="$product->primary_image">
    <!-- JSON-LD Product Schema -->
    <script type="application/ld+json">
    {
        "{{ '@context' }}": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "image": {{ json_encode($images) }},
        "description": "{{ $product->short_description }}",
        "sku": "{{ $product->sku }}",
        "brand": {
            "@type": "Brand",
            "name": "{{ $product->brand?->name ?? 'ZYRICZ' }}"
        },
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "USD",
            "price": "{{ $product->price }}",
            "availability": "{{ $product->is_in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ $product->rating_average }}",
            "reviewCount": "{{ max(1, $product->reviews_count) }}"
        }
    }
    </script>

    <!-- Breadcrumb -->
    <div class="bg-zinc-950 border-b border-zinc-900 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs text-zinc-500">
                <a href="{{ route('home') }}" class="hover:text-zinc-300">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-zinc-300">Collections</a>
                @if($product->categories->first())
                    <span>/</span>
                    <a href="{{ route('products.index', ['category' => $product->categories->first()->slug]) }}" class="hover:text-zinc-300">
                        {{ $product->categories->first()->name }}
                    </a>
                @endif
                <span>/</span>
                <span class="text-white truncate">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Details Section -->
    <section class="py-12 bg-zinc-950"
             x-data="{
                activeImage: '{{ $images[0] }}',
                selectedVariant: {{ $hasVariants ? $variants->first()->id : 'null' }},
                selectedVariantPrice: {{ $hasVariants ? $variants->first()->calculated_price : (float)$product->price }},
                selectedVariantSku: '{{ $hasVariants ? $variants->first()->sku : $product->sku }}',
                selectedVariantStock: {{ $hasVariants ? $variants->first()->stock : $product->stock }},
                qty: 1,
                activeTab: 'specs',
                saved: {{ app(\App\Services\WishlistService::class)->has($product->id) ? 'true' : 'false' }},
                reviewModal: false,
                setVariant(id, price, sku, stock) {
                    this.selectedVariant = id;
                    this.selectedVariantPrice = price;
                    this.selectedVariantSku = sku;
                    this.selectedVariantStock = stock;
                    if (this.qty > stock) this.qty = Math.max(1, stock);
                }
             }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                <!-- Left: Media Gallery -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Main Preview Image -->
                    <div class="relative aspect-square w-full overflow-hidden rounded-3xl glass-card bg-zinc-900 ring-1 ring-white/10 group">
                        <img :src="activeImage"
                             alt="{{ $product->name }}"
                             class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105">

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                            @if($product->discount_percentage)
                                <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-black uppercase tracking-wider text-zinc-950 shadow-lg">
                                    Save {{ $product->discount_percentage }}%
                                </span>
                            @endif
                            @if($product->is_bestseller)
                                <span class="rounded-full bg-zinc-950/90 border border-amber-500/30 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-400 backdrop-blur-md">
                                    Atelier Bestseller
                                </span>
                            @endif
                        </div>

                        <!-- Wishlist Button -->
                        <button type="button"
                                @click="saved = await $store.wishlist.toggle({{ $product->id }})"
                                class="absolute top-4 right-4 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-zinc-950/80 border border-white/10 backdrop-blur-md text-zinc-300 transition hover:scale-110 hover:text-rose-500"
                                :class="{ 'text-rose-500 !border-rose-500/40 !bg-rose-500/10': saved }"
                                title="Save to Wishlist">
                            <svg class="h-5 w-5" :fill="saved ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Thumbnails Strip -->
                    @if(count($images) > 1)
                        <div class="flex items-center gap-3 overflow-x-auto pb-2">
                            @foreach($images as $img)
                                <button type="button"
                                        @click="activeImage = '{{ $img }}'"
                                        class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border-2 transition"
                                        :class="activeImage === '{{ $img }}' ? 'border-amber-400 ring-2 ring-amber-400/20' : 'border-zinc-800 opacity-60 hover:opacity-100'">
                                    <img src="{{ $img }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: Product Info & Actions -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <!-- Brand Label -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-widest text-amber-500">
                                {{ $product->brand?->name ?? 'ZYRICZ ATELIER' }}
                            </span>
                            <span class="font-mono text-xs text-zinc-500" x-text="'SKU: ' + selectedVariantSku">
                                SKU: {{ $product->sku }}
                            </span>
                        </div>

                        <!-- Product Title -->
                        <h1 class="mt-2 text-2xl sm:text-3xl font-black text-white tracking-tight">
                            {{ $product->name }}
                        </h1>

                        <!-- Ratings Recap -->
                        <div class="mt-3 flex items-center gap-3 text-xs">
                            <div class="flex items-center gap-1 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($product->rating_average) ? '★' : '☆' }}</span>
                                @endfor
                                <span class="font-mono font-bold text-white ml-1">{{ number_format($product->rating_average, 1) }}</span>
                            </div>
                            <span class="text-zinc-600">•</span>
                            <a href="#reviews-section" class="text-zinc-400 hover:text-amber-400 underline">
                                {{ $totalReviews }} Verified Reviews
                            </a>
                        </div>
                    </div>

                    <!-- Pricing Banner -->
                    <div class="flex items-baseline gap-3 rounded-2xl border border-zinc-800 bg-zinc-900/60 p-4">
                        <span class="font-mono text-3xl font-black text-amber-400"
                              x-text="'$' + Number(selectedVariantPrice).toFixed(2)">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @if($product->compare_at_price && $product->compare_at_price > $product->price)
                            <span class="font-mono text-sm text-zinc-500 line-through">
                                ${{ number_format($product->compare_at_price, 2) }}
                            </span>
                        @endif
                        <span class="text-xs text-zinc-400 ml-auto">Taxes calculated at checkout</span>
                    </div>

                    <!-- Stock Status Alert -->
                    <div>
                        <template x-if="selectedVariantStock > 5">
                            <div class="flex items-center gap-2 text-xs text-emerald-400 font-semibold">
                                <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>In Stock & Ready for Immediate Dispatch</span>
                            </div>
                        </template>
                        <template x-if="selectedVariantStock > 0 && selectedVariantStock <= 5">
                            <div class="flex items-center gap-2 text-xs text-amber-400 font-semibold">
                                <span class="flex h-2 w-2 rounded-full bg-amber-400"></span>
                                <span x-text="'Limited Inventory: Only ' + selectedVariantStock + ' Pieces Remaining'"></span>
                            </div>
                        </template>
                        <template x-if="selectedVariantStock <= 0">
                            <div class="flex items-center gap-2 text-xs text-rose-400 font-semibold">
                                <span class="flex h-2 w-2 rounded-full bg-rose-400"></span>
                                <span>Currently Allocated / Out of Stock</span>
                            </div>
                        </template>
                    </div>

                    <!-- Short Description Narrative -->
                    <p class="text-xs text-zinc-300 leading-relaxed">
                        {{ $product->short_description }}
                    </p>

                    <!-- Variants Selection -->
                    @if($hasVariants)
                        <div class="space-y-3 pt-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-white">Select Finish / Specification</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @foreach($variants as $variant)
                                    <button type="button"
                                            @click="setVariant({{ $variant->id }}, {{ $variant->calculated_price }}, '{{ $variant->sku }}', {{ $variant->stock }})"
                                            class="flex items-center justify-between rounded-xl border p-3 text-left transition"
                                            :class="selectedVariant === {{ $variant->id }} ? 'border-amber-400 bg-amber-500/10 text-white ring-1 ring-amber-400' : 'border-zinc-800 bg-zinc-900/80 text-zinc-300 hover:border-zinc-700'">
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-semibold">{{ $variant->name }}</p>
                                            <p class="font-mono text-[11px] text-zinc-500">{{ $variant->sku }}</p>
                                        </div>
                                        <span class="font-mono text-xs font-bold text-amber-400">
                                            ${{ number_format($variant->calculated_price, 2) }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quantity & Add To Bag -->
                    <div class="space-y-3 pt-4 border-t border-zinc-900">
                        <div class="flex items-center gap-4">
                            <!-- Qty Counter -->
                            <div class="flex items-center rounded-xl border border-zinc-800 bg-zinc-900 p-1">
                                <button type="button"
                                        @click="qty = Math.max(1, qty - 1)"
                                        class="h-9 w-9 text-zinc-400 hover:text-white transition flex items-center justify-center font-bold">
                                    -
                                </button>
                                <span class="w-10 text-center font-mono text-sm font-bold text-white" x-text="qty"></span>
                                <button type="button"
                                        @click="qty = Math.min(selectedVariantStock, qty + 1)"
                                        class="h-9 w-9 text-zinc-400 hover:text-white transition flex items-center justify-center font-bold">
                                    +
                                </button>
                            </div>

                            <!-- Primary Add to Bag CTA -->
                            <button type="button"
                                    @click="$store.cart.addItem({{ $product->id }}, qty, selectedVariant)"
                                    :disabled="selectedVariantStock <= 0"
                                    class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 py-3.5 text-xs font-bold uppercase tracking-wider text-zinc-950 shadow-xl shadow-amber-500/20 hover:from-amber-300 hover:to-amber-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Add To Bag</span>
                            </button>
                        </div>

                        <!-- Instant Buy Now Direct To Checkout -->
                        <button type="button"
                                @click="await $store.cart.addItem({{ $product->id }}, qty, selectedVariant); window.location.href='{{ route('checkout.index') }}';"
                                :disabled="selectedVariantStock <= 0"
                                class="w-full flex items-center justify-center rounded-xl border border-zinc-800 bg-zinc-900 py-3 text-xs font-bold uppercase tracking-wider text-zinc-200 hover:border-zinc-700 hover:bg-zinc-800 transition">
                            Instant Checkout &rarr;
                        </button>
                    </div>

                    <!-- Trust Signals -->
                    <div class="grid grid-cols-3 gap-3 pt-4 border-t border-zinc-900 text-center text-[11px] text-zinc-400">
                        <div class="p-2 rounded-xl bg-zinc-900/40 border border-zinc-800/60">
                            <span class="block text-amber-400 font-bold">Complimentary</span>
                            <span>Insured Delivery</span>
                        </div>
                        <div class="p-2 rounded-xl bg-zinc-900/40 border border-zinc-800/60">
                            <span class="block text-amber-400 font-bold">30 Days</span>
                            <span>Atelier Trial</span>
                        </div>
                        <div class="p-2 rounded-xl bg-zinc-900/40 border border-zinc-800/60">
                            <span class="block text-amber-400 font-bold">Serialized</span>
                            <span>Authenticity Seal</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Specifications & Description -->
            <div class="mt-20 border-t border-zinc-900 pt-10">
                <div class="flex items-center justify-center gap-8 border-b border-zinc-900 pb-4 text-xs font-bold uppercase tracking-wider">
                    <button type="button"
                            @click="activeTab = 'specs'"
                            :class="activeTab === 'specs' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                        Technical Specifications
                    </button>
                    <button type="button"
                            @click="activeTab = 'description'"
                            :class="activeTab === 'description' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                        Detailed Description
                    </button>
                    <button type="button"
                            @click="activeTab = 'shipping'"
                            :class="activeTab === 'shipping' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                        Courier & Transit Policy
                    </button>
                </div>

                <div class="py-8 max-w-4xl mx-auto">
                    <!-- Specs Tab -->
                    <div x-show="activeTab === 'specs'" class="space-y-4">
                        @if($product->specifications && is_array($product->specifications))
                            <div class="rounded-2xl border border-zinc-800 overflow-hidden">
                                <table class="w-full text-xs text-left">
                                    <tbody class="divide-y divide-zinc-800">
                                        @foreach($product->specifications as $key => $val)
                                            <tr class="hover:bg-zinc-900/50 transition">
                                                <td class="py-3.5 px-6 font-semibold text-zinc-400 w-1/3 bg-zinc-900/30">{{ $key }}</td>
                                                <td class="py-3.5 px-6 text-white font-mono">{{ $val }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-xs text-zinc-400 text-center py-6">Standard bespoke atelier specifications apply.</p>
                        @endif
                    </div>

                    <!-- Description Tab -->
                    <div x-show="activeTab === 'description'" class="prose prose-invert max-w-none text-xs text-zinc-300 leading-relaxed space-y-4">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <!-- Shipping Tab -->
                    <div x-show="activeTab === 'shipping'" class="text-xs text-zinc-400 space-y-3 leading-relaxed">
                        <p>All orders from ZYRICZ are packed in tamper-evident security containers with high-density archival foam and dispatched via insured courier.</p>
                        <p><strong class="text-white">Complimentary Delivery:</strong> Orders exceeding $150 USD receive complimentary 2-3 business day courier express dispatch.</p>
                        <p><strong class="text-white">White Glove Courier:</strong> Available for Swiss horology orders with direct signature requirement upon handover.</p>
                    </div>
                </div>
            </div>

            <!-- Customer Reviews Section -->
            <div id="reviews-section" class="mt-16 border-t border-zinc-900 pt-16">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-black text-white">Client Reviews & Verification</h2>
                        <p class="text-xs text-zinc-400 mt-1">Honest feedback from owners and horological collectors.</p>
                    </div>
                    <button type="button"
                            @click="reviewModal = true"
                            class="rounded-xl bg-zinc-800 border border-zinc-700 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white hover:bg-zinc-700 transition">
                        Write A Review
                    </button>
                </div>

                <!-- Rating Overview Card -->
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div class="text-center md:border-r md:border-zinc-800 py-2">
                        <p class="font-mono text-4xl font-black text-amber-400">{{ number_format($product->rating_average, 1) }}</p>
                        <div class="flex justify-center gap-1 text-amber-400 text-sm mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($product->rating_average) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">Based on {{ $totalReviews }} evaluations</p>
                    </div>

                    <!-- Distribution Bars -->
                    <div class="md:col-span-2 space-y-1.5 text-xs">
                        @foreach([5, 4, 3, 2, 1] as $star)
                            @php
                                $cnt = $ratingDistribution[$star] ?? 0;
                                $pct = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="w-12 font-mono text-zinc-400">{{ $star }} Star</span>
                                <div class="flex-1 h-2 rounded-full bg-zinc-800 overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="w-8 text-right font-mono text-zinc-500 text-[11px]">{{ $cnt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews List -->
                @if($product->approvedReviews->isEmpty())
                    <p class="text-xs text-zinc-500 text-center py-8">Be the first to leave a review for this creation.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->approvedReviews as $rev)
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">{{ $rev->customer_name }}</span>
                                        @if($rev->is_verified_purchase)
                                            <span class="rounded bg-emerald-500/10 text-emerald-400 px-2 py-0.5 text-[10px] font-semibold border border-emerald-500/20">
                                                Verified Buyer
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-amber-400 text-xs">
                                        @for($i = 0; $i < $rev->rating; $i++)
                                            <span>★</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($rev->title)
                                    <h4 class="text-xs font-bold text-zinc-200">&ldquo;{{ $rev->title }}&rdquo;</h4>
                                @endif
                                <p class="text-xs text-zinc-400 leading-relaxed">{{ $rev->comment }}</p>
                                <p class="text-[10px] font-mono text-zinc-600">{{ $rev->created_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
                <div class="mt-20 border-t border-zinc-900 pt-16">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-amber-500">Curated Pairings</span>
                            <h2 class="text-2xl font-black text-white mt-1">You May Also Appreciate</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $rel)
                            <x-product-card :product="$rel" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Review Submission Modal -->
        <div x-show="reviewModal"
             x-cloak
             class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
            <div class="w-full max-w-lg rounded-3xl border border-zinc-800 bg-zinc-900 p-6 shadow-2xl space-y-4"
                 @click.outside="reviewModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                    <h3 class="text-base font-bold text-white">Write a Verified Review</h3>
                    <button type="button" @click="reviewModal = false" class="text-zinc-400 hover:text-white">✕</button>
                </div>

                <form action="{{ route('products.reviews.store', $product->slug) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1">Rating</label>
                        <select name="rating" class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-2 text-xs text-white">
                            <option value="5">★★★★★ (5 Stars - Exceptional)</option>
                            <option value="4">★★★★☆ (4 Stars - Highly Recommend)</option>
                            <option value="3">★★★☆☆ (3 Stars - Average)</option>
                            <option value="2">★★☆☆☆ (2 Stars - Disappointed)</option>
                            <option value="1">★☆☆☆☆ (1 Star - Poor)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1">Your Name</label>
                            <input type="text"
                                   name="customer_name"
                                   value="{{ auth()->user()?->name }}"
                                   required
                                   class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-2 text-xs text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1">Email</label>
                            <input type="email"
                                   name="customer_email"
                                   value="{{ auth()->user()?->email }}"
                                   required
                                   class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-2 text-xs text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1">Headline</label>
                        <input type="text"
                               name="title"
                               placeholder="e.g. Pure horological perfection"
                               class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-2 text-xs text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1">Your Review</label>
                        <textarea name="comment"
                                  rows="4"
                                  required
                                  placeholder="Share your experience with this item..."
                                  class="w-full rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-2 text-xs text-white"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button"
                                @click="reviewModal = false"
                                class="rounded-xl border border-zinc-800 px-4 py-2 text-xs font-bold text-zinc-400 hover:text-white">
                            Cancel
                        </button>
                        <button type="submit"
                                class="rounded-xl bg-amber-500 px-6 py-2 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                            Publish Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout>
