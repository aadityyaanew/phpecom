<x-layout title="Luxury Horology, Reference Acoustics & Everyday Essentials">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-zinc-950 py-20 lg:py-28">
        <!-- Background Ambient Glow -->
        <div class="pointer-events-none absolute -top-40 right-0 h-[600px] w-[600px] rounded-full bg-amber-500/10 blur-[140px]"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-[400px] w-[400px] rounded-full bg-amber-600/5 blur-[120px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left: Headline & Actions -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-500/30 bg-amber-500/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-widest text-amber-400 backdrop-blur-md">
                        <span class="flex h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                        <span>2026 Atelier Collection Available</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.08]">
                        The Art of <span class="gold-gradient-text">Modern Precision</span> & Tactile Living.
                    </h1>

                    <p class="text-base sm:text-lg text-zinc-400 max-w-xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Meticulously sculpted horology, reference-grade beryllium acoustics, and vegetable-tanned Tuscan carry essentials. Designed for those who savor uncompromised tactile perfection.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('products.index') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-8 py-4 text-xs font-bold uppercase tracking-wider text-zinc-950 shadow-xl shadow-amber-500/20 hover:from-amber-300 hover:to-amber-500 transition-all hover:scale-[1.02]">
                            <span>Explore Catalog</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'luxury-timepieces']) }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-zinc-800 bg-zinc-900/80 px-8 py-4 text-xs font-bold uppercase tracking-wider text-zinc-200 hover:border-zinc-700 hover:bg-zinc-800 transition">
                            Horology Atelier
                        </a>
                    </div>

                    <!-- Metrics Bar -->
                    <div class="pt-8 border-t border-zinc-900 grid grid-cols-3 gap-6 text-center lg:text-left">
                        <div>
                            <p class="font-mono text-2xl font-black text-white">4.95★</p>
                            <p class="text-xs text-zinc-500">Verified Client Rating</p>
                        </div>
                        <div>
                            <p class="font-mono text-2xl font-black text-white">100%</p>
                            <p class="text-xs text-zinc-500">Grade 5 Titanium & Leather</p>
                        </div>
                        <div>
                            <p class="font-mono text-2xl font-black text-white">48h</p>
                            <p class="text-xs text-zinc-500">Worldwide Insured Dispatch</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Featured Hero Visual Card -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-3xl overflow-hidden glass-card p-3 shadow-2xl ring-1 ring-white/10 group">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80"
                             alt="Zyricz Horizon Spatial Headphones"
                             class="h-[460px] w-full object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700">

                        <!-- Floating Showcase Tag -->
                        <div class="absolute bottom-6 inset-x-6 rounded-2xl border border-white/10 bg-zinc-950/85 p-4 backdrop-blur-xl flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-widest text-amber-400 font-bold">Featured Signature</span>
                                <h3 class="text-sm font-bold text-white">Horizon Spatial Acoustics</h3>
                                <p class="text-xs font-mono text-zinc-400 mt-0.5">$399.00 <span class="text-zinc-600 line-through">$479.00</span></p>
                            </div>
                            <a href="{{ route('products.show', 'zyricz-horizon-spatial-headphones') }}"
                               class="rounded-xl bg-amber-500 px-4 py-2 text-xs font-bold text-zinc-950 hover:bg-amber-400 transition">
                                Inspect &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Curated Categories Grid -->
    <section class="py-16 bg-zinc-950 border-t border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest text-amber-500 font-bold">The Catalog</span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1">Curated Collections</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs font-bold text-amber-400 hover:underline uppercase tracking-wider flex items-center gap-1">
                    <span>View All Collections</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="group relative h-72 overflow-hidden rounded-2xl glass-card glass-card-hover p-6 flex flex-col justify-end">
                        <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}"
                             alt="{{ $category->name }}"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/60 to-transparent"></div>

                        <div class="relative z-10 space-y-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400">
                                {{ $category->products_count }} Creations
                            </span>
                            <h3 class="text-xl font-bold text-white group-hover:text-amber-400 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-zinc-400 line-clamp-2">
                                {{ $category->description }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Creations Grid -->
    <section class="py-16 bg-zinc-900/30 border-t border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest text-amber-500 font-bold">Signature Drops</span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white mt-1">Featured Atelier Creations</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-400">
                        ● All in Ready Stock
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Atelier Craftsmanship Spotlight -->
    <section class="py-20 bg-zinc-950 border-t border-zinc-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="relative rounded-3xl overflow-hidden glass-card p-3 shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80"
                         alt="Horology Assembly"
                         class="h-96 sm:h-[450px] w-full object-cover rounded-2xl">
                    <div class="absolute bottom-6 left-6 right-6 rounded-xl border border-white/10 bg-zinc-950/85 p-4 backdrop-blur-md">
                        <p class="text-xs font-mono text-amber-400 uppercase tracking-widest font-bold">Micro-Engineering</p>
                        <p class="text-sm font-semibold text-white mt-0.5">Tolerance calibrated to &plusmn;0.002mm on multi-axis CNC lathes.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-500">The Zyricz Creed</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                        Objects Crafted Not For A Season, But For A Generation.
                    </h2>
                    <p class="text-sm text-zinc-400 leading-relaxed">
                        Every curve, detent click, and stitching angle is evaluated obsessively. We partner exclusively with master horologists in Geneva, acoustic artisans in Copenhagen, and heritage tanneries in Florence.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400 font-bold text-xs">
                                01
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Aerospace Titanium & Sapphire</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Grade 5 titanium alloy delivers twice the tensile resilience at half the mass of stainless steel.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400 font-bold text-xs">
                                02
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Acoustic Beryllium Drivers</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Ultralight diaphragms capable of reaching 45kHz without acoustic distortion or compression.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400 font-bold text-xs">
                                03
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Full-Grain Tuscan Vegetable Patina</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Bark and mimosa-extract tanned hides that absorb stories, deepening in radiance each year.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Connoisseur Testimonials -->
    <section class="py-16 bg-zinc-900/40 border-t border-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12 space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-500">Verified Testimonials</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white">Words From Collectors</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <div class="rounded-2xl glass-card p-6 flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-1 text-amber-400 text-sm">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <span>★</span>
                                @endfor
                            </div>
                            @if($review->title)
                                <h4 class="text-sm font-bold text-white">&ldquo;{{ $review->title }}&rdquo;</h4>
                            @endif
                            <p class="text-xs text-zinc-400 leading-relaxed">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-zinc-800/80 flex items-center justify-between text-xs">
                            <div>
                                <p class="font-bold text-white">{{ $review->customer_name }}</p>
                                <p class="text-[11px] text-zinc-500">{{ $review->product?->name }}</p>
                            </div>
                            <span class="rounded bg-emerald-500/10 text-emerald-400 px-2 py-0.5 text-[10px] font-semibold border border-emerald-500/20">
                                Verified
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Promotion Banner -->
    <section class="py-16 bg-gradient-to-b from-zinc-950 to-zinc-900 border-t border-zinc-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="rounded-full bg-amber-500/10 border border-amber-500/30 px-3 py-1 text-xs font-bold uppercase tracking-widest text-amber-400">
                VIP Concierge Invitation
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">
                Receive <span class="gold-gradient-text">10% Off</span> Your Inaugural Order.
            </h2>
            <p class="text-sm text-zinc-400 max-w-lg mx-auto">
                Join our private clientele roster for secret prototype previews and bespoke horology allocations.
            </p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="max-w-md mx-auto flex gap-2">
                @csrf
                <input type="email"
                       name="email"
                       required
                       placeholder="Enter your email address..."
                       class="flex-1 rounded-xl border border-zinc-800 bg-zinc-900/90 px-4 py-3 text-xs text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none">
                <button type="submit"
                        class="rounded-xl bg-amber-500 px-6 py-3 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                    Unlock Code
                </button>
            </form>
        </div>
    </section>
</x-layout>
