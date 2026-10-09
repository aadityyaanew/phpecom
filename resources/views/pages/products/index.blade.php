<x-layout title="Atelier Collections & Curations">
    <!-- Breadcrumb & Header -->
    <div class="bg-zinc-950 border-b border-zinc-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <nav class="flex items-center gap-2 text-xs text-zinc-500 mb-2">
                        <a href="{{ route('home') }}" class="hover:text-zinc-300">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Collections</span>
                        @if($activeCategory)
                            <span>/</span>
                            <span class="text-white">{{ $activeCategory->name }}</span>
                        @endif
                    </nav>
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        {{ $activeCategory ? $activeCategory->name : ($activeBrand ? $activeBrand->name : 'All Collections') }}
                    </h1>
                    <p class="text-xs text-zinc-400 mt-1 max-w-xl">
                        {{ $activeCategory ? $activeCategory->description : 'Engineered with aerospace materials, horological precision, and minimalist beauty.' }}
                    </p>
                </div>

                <!-- Product Count & Current Query -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="rounded-full bg-zinc-900 border border-zinc-800 px-3.5 py-1.5 text-xs font-mono text-zinc-300">
                        {{ $products->total() }} Objects Found
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Catalog View -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ mobileFilter: false }">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Desktop Filters Sidebar -->
            <aside class="hidden lg:block space-y-8">
                <form action="{{ route('products.index') }}" method="GET" id="filterForm" class="space-y-6">
                    <!-- Preserve search query if present -->
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- Active Filters Header with Reset -->
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-white">Refine Objects</h3>
                        @if(request()->hasAny(['category', 'brand', 'min_price', 'max_price', 'in_stock', 'on_sale', 'q', 'sort']))
                            <a href="{{ route('products.index') }}" class="text-[11px] font-semibold text-rose-400 hover:underline">
                                Reset All
                            </a>
                        @endif
                    </div>

                    <!-- Category Filter -->
                    <div class="space-y-2.5">
                        <label class="text-xs font-bold uppercase tracking-wider text-amber-500">Categories</label>
                        <div class="space-y-1.5">
                            <label class="flex items-center justify-between text-xs cursor-pointer py-1 text-zinc-300 hover:text-white">
                                <span class="flex items-center gap-2">
                                    <input type="radio"
                                           name="category"
                                           value=""
                                           onchange="this.form.submit()"
                                           {{ !request('category') ? 'checked' : '' }}
                                           class="rounded text-amber-500 focus:ring-0">
                                    <span>All Categories</span>
                                </span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="flex items-center justify-between text-xs cursor-pointer py-1 text-zinc-400 hover:text-white">
                                    <span class="flex items-center gap-2">
                                        <input type="radio"
                                               name="category"
                                               value="{{ $cat->slug }}"
                                               onchange="this.form.submit()"
                                               {{ request('category') === $cat->slug ? 'checked' : '' }}
                                               class="rounded text-amber-500 focus:ring-0">
                                        <span class="{{ request('category') === $cat->slug ? 'text-amber-400 font-semibold' : '' }}">{{ $cat->name }}</span>
                                    </span>
                                    <span class="font-mono text-[10px] text-zinc-600">({{ $cat->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="space-y-2.5 pt-4 border-t border-zinc-900">
                        <label class="text-xs font-bold uppercase tracking-wider text-amber-500">Brands</label>
                        <div class="space-y-1.5">
                            <label class="flex items-center justify-between text-xs cursor-pointer py-1 text-zinc-300 hover:text-white">
                                <span class="flex items-center gap-2">
                                    <input type="radio"
                                           name="brand"
                                           value=""
                                           onchange="this.form.submit()"
                                           {{ !request('brand') ? 'checked' : '' }}
                                           class="rounded text-amber-500 focus:ring-0">
                                    <span>All Brands</span>
                                </span>
                            </label>
                            @foreach($brands as $b)
                                <label class="flex items-center justify-between text-xs cursor-pointer py-1 text-zinc-400 hover:text-white">
                                    <span class="flex items-center gap-2">
                                        <input type="radio"
                                               name="brand"
                                               value="{{ $b->slug }}"
                                               onchange="this.form.submit()"
                                               {{ request('brand') === $b->slug ? 'checked' : '' }}
                                               class="rounded text-amber-500 focus:ring-0">
                                        <span class="{{ request('brand') === $b->slug ? 'text-amber-400 font-semibold' : '' }}">{{ $b->name }}</span>
                                    </span>
                                    <span class="font-mono text-[10px] text-zinc-600">({{ $b->products_count }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="space-y-2.5 pt-4 border-t border-zinc-900">
                        <label class="text-xs font-bold uppercase tracking-wider text-amber-500">Price ($ USD)</label>
                        <div class="flex items-center gap-2">
                            <input type="number"
                                   name="min_price"
                                   placeholder="Min"
                                   value="{{ request('min_price') }}"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-3 py-1.5 text-xs text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none">
                            <span class="text-zinc-600">-</span>
                            <input type="number"
                                   name="max_price"
                                   placeholder="Max"
                                   value="{{ request('max_price') }}"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-3 py-1.5 text-xs text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none">
                        </div>
                        <button type="submit"
                                class="w-full rounded-xl border border-zinc-800 bg-zinc-900/80 py-2 text-xs font-semibold text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                            Apply Price Filter
                        </button>
                    </div>

                    <!-- Stock & Sale Toggles -->
                    <div class="space-y-3 pt-4 border-t border-zinc-900">
                        <label class="flex items-center gap-2.5 text-xs text-zinc-300 cursor-pointer">
                            <input type="checkbox"
                                   name="in_stock"
                                   value="1"
                                   onchange="this.form.submit()"
                                   {{ request('in_stock') ? 'checked' : '' }}
                                   class="rounded border-zinc-700 bg-zinc-900 text-amber-500 focus:ring-0">
                            <span>Ready Stock Only</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-zinc-300 cursor-pointer">
                            <input type="checkbox"
                                   name="on_sale"
                                   value="1"
                                   onchange="this.form.submit()"
                                   {{ request('on_sale') ? 'checked' : '' }}
                                   class="rounded border-zinc-700 bg-zinc-900 text-amber-500 focus:ring-0">
                            <span>Promotional Markdown Only</span>
                        </label>
                    </div>
                </form>
            </aside>

            <!-- Products Content Area -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Sorting & Mobile Filter Toggle -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-zinc-800/80 bg-zinc-900/60 p-4">
                    <!-- Mobile Filter Trigger -->
                    <button type="button"
                            @click="mobileFilter = true"
                            class="lg:hidden flex items-center justify-center gap-2 rounded-xl border border-zinc-700 bg-zinc-800 px-4 py-2 text-xs font-bold text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Filter Objects</span>
                    </button>

                    <!-- Active Filter Chips -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request('q'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 px-3 py-1 text-xs text-amber-400">
                                <span>Query: "{{ request('q') }}"</span>
                                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-white">&times;</a>
                            </span>
                        @endif
                        @if(request('category'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-800 px-3 py-1 text-xs text-zinc-300">
                                <span>Category: {{ $activeCategory?->name ?? request('category') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-white">&times;</a>
                            </span>
                        @endif
                        @if(request('brand'))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-800 px-3 py-1 text-xs text-zinc-300">
                                <span>Brand: {{ $activeBrand?->name ?? request('brand') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="hover:text-white">&times;</a>
                            </span>
                        @endif
                    </div>

                    <!-- Sorting Dropdown -->
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <label for="sort" class="text-xs text-zinc-400 hidden sm:inline">Sort By:</label>
                        <select name="sort"
                                id="sort"
                                onchange="location = this.value;"
                                class="rounded-xl border border-zinc-700 bg-zinc-950 px-3 py-1.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" {{ request('sort', 'featured') === 'featured' ? 'selected' : '' }}>
                                Curated / Featured
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                Newest Additions
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>
                                Highest Rating
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->isEmpty())
                    <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-12 text-center space-y-4">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-800 text-zinc-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-white">No creations match criteria</h3>
                            <p class="text-xs text-zinc-400 max-w-sm mx-auto">Try clearing selected filters or searching with alternative keywords.</p>
                        </div>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex rounded-xl bg-amber-500 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                            Reset Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Filter Sheet -->
        <div x-show="mobileFilter"
             x-cloak
             class="fixed inset-0 z-50 lg:hidden"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="mobileFilter = false"></div>
            <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-zinc-900 p-6 shadow-2xl overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-zinc-800">
                    <h3 class="text-sm font-bold text-white uppercase">Filters</h3>
                    <button type="button" @click="mobileFilter = false" class="text-zinc-400 hover:text-white">
                        ✕
                    </button>
                </div>
                <div class="mt-4">
                    <!-- Cloned filters logic -->
                    <a href="{{ route('products.index') }}" class="block text-xs text-rose-400 font-semibold mb-4">
                        Reset All Filters
                    </a>
                    <div class="space-y-4 text-xs">
                        <p class="font-bold text-amber-500 uppercase">Categories</p>
                        @foreach($categories as $cat)
                            <a href="{{ request()->fullUrlWithQuery(['category' => $cat->slug]) }}"
                               class="block py-1 {{ request('category') === $cat->slug ? 'text-amber-400 font-bold' : 'text-zinc-300' }}">
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
