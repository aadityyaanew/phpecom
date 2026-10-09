@props(['product'])

@php
    $discount = $product->discount_percentage;
    $isSaved = app(\App\Services\WishlistService::class)->has($product->id);
@endphp

<div class="group relative flex flex-col overflow-hidden rounded-2xl glass-card glass-card-hover"
     x-data="{ saved: {{ $isSaved ? 'true' : 'false' }}, loading: false }">

    <!-- Image Container -->
    <div class="relative aspect-square w-full overflow-hidden bg-zinc-900">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->primary_image }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 class="h-full w-full object-cover object-center product-image-zoom">
        </a>

        <!-- Badges -->
        <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            @if($discount)
                <span class="rounded-full bg-amber-500 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-zinc-950 shadow-md">
                    -{{ $discount }}%
                </span>
            @endif
            @if($product->is_bestseller)
                <span class="rounded-full bg-zinc-900/90 border border-amber-500/30 px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-amber-400 backdrop-blur-md">
                    Atelier Choice
                </span>
            @endif
            @if($product->is_low_stock)
                <span class="rounded-full bg-rose-500/90 px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-md">
                    Only {{ $product->stock }} Left
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button type="button"
                @click="saved = await $store.wishlist.toggle({{ $product->id }})"
                class="absolute top-3 right-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-zinc-950/70 border border-white/10 backdrop-blur-md text-zinc-300 transition hover:scale-110 hover:text-rose-500"
                :class="{ 'text-rose-500 !border-rose-500/40 !bg-rose-500/10': saved }"
                title="Add to Wishlist">
            <svg class="h-4 w-4" :fill="saved ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        <!-- Quick Add Overlay (Desktop) -->
        <div class="absolute inset-x-3 bottom-3 z-10 hidden sm:flex translate-y-4 opacity-0 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
            <button type="button"
                    @click="$store.cart.addItem({{ $product->id }})"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500/95 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 backdrop-blur-md shadow-lg hover:bg-amber-400 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Quick Add</span>
            </button>
        </div>
    </div>

    <!-- Product Info -->
    <div class="flex flex-1 flex-col justify-between p-4 sm:p-5">
        <div>
            <!-- Brand & Category -->
            <div class="flex items-center justify-between text-[11px] text-zinc-400">
                <span class="uppercase tracking-widest text-amber-500/90 font-semibold truncate">
                    {{ $product->brand?->name ?? 'ZYRICZ ATELIER' }}
                </span>
                <div class="flex items-center gap-1 text-amber-400">
                    <span>★</span>
                    <span class="font-mono font-medium text-zinc-300">{{ number_format($product->rating_average, 1) }}</span>
                    <span class="text-zinc-500 text-[10px]">({{ $product->reviews_count }})</span>
                </div>
            </div>

            <!-- Title -->
            <h3 class="mt-2 text-sm font-semibold text-white group-hover:text-amber-400 transition-colors line-clamp-1">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Short Description -->
            <p class="mt-1 text-xs text-zinc-400 line-clamp-2 leading-relaxed">
                {{ $product->short_description }}
            </p>
        </div>

        <!-- Pricing & Mobile Action -->
        <div class="mt-4 flex items-center justify-between pt-3 border-t border-zinc-800/80">
            <div class="flex items-baseline gap-2">
                <span class="font-mono text-base font-bold text-white">
                    ${{ number_format($product->price, 2) }}
                </span>
                @if($product->compare_at_price && $product->compare_at_price > $product->price)
                    <span class="font-mono text-xs text-zinc-500 line-through">
                        ${{ number_format($product->compare_at_price, 2) }}
                    </span>
                @endif
            </div>

            <!-- Mobile Quick Add -->
            <button type="button"
                    @click="$store.cart.addItem({{ $product->id }})"
                    class="sm:hidden flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-zinc-950 shadow-sm"
                    title="Add to Bag">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>
</div>
