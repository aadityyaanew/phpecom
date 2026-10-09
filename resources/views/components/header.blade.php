@php
    $categories = \App\Models\Category::active()->orderBy('sort_order')->take(5)->get();
@endphp

<header class="sticky top-0 z-40 w-full" x-data="{ mobileMenu: false, searchOpen: false }">
    <!-- Announcement Bar -->
    <div class="bg-gradient-to-r from-amber-600 via-amber-500 to-amber-700 py-1.5 px-4 text-center text-xs font-semibold uppercase tracking-widest text-zinc-950 shadow-inner">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="hidden md:inline-block text-[11px] opacity-80">Worldwide Insured Transit</span>
            <div class="flex items-center justify-center gap-2 mx-auto">
                <span>Complimentary Express Delivery Over $150</span>
                <span class="opacity-40">•</span>
                <span>Code <span class="bg-zinc-950 text-amber-400 px-1.5 py-0.5 rounded font-mono text-[10px] tracking-normal">WELCOME10</span> For 10% Off</span>
            </div>
            <a href="{{ route('orders.track') }}" class="hidden md:inline-flex items-center gap-1 text-[11px] hover:underline opacity-90">
                Track Shipment &rarr;
            </a>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="glass-header px-4 sm:px-6 lg:px-8 transition-colors">
        <div class="max-w-7xl mx-auto flex h-20 items-center justify-between gap-4">
            <!-- Left: Mobile Menu Trigger + Brand Logo -->
            <div class="flex items-center gap-4">
                <button type="button"
                        @click="mobileMenu = !mobileMenu"
                        class="lg:hidden p-2 text-zinc-400 hover:text-white transition"
                        aria-label="Toggle Navigation">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 via-amber-500 to-amber-700 text-zinc-950 shadow-lg shadow-amber-500/20 ring-1 ring-amber-400/40 group-hover:scale-105 transition-transform">
                        <span class="font-mono text-xl font-black tracking-tighter">Z</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-[0.25em] text-white uppercase group-hover:text-amber-400 transition-colors">
                            ZYRICZ
                        </span>
                        <span class="text-[9px] font-semibold tracking-widest text-amber-500 uppercase">
                            Atelier &bull; Horology &bull; Sound
                        </span>
                    </div>
                </a>
            </div>

            <!-- Center: Navigation Links -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('products.index') }}"
                   class="text-sm font-medium text-zinc-300 hover:text-amber-400 transition-colors {{ request()->routeIs('products.index') && !request('category') ? 'text-amber-400 font-semibold' : '' }}">
                    All Collections
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="text-sm font-medium text-zinc-300 hover:text-amber-400 transition-colors {{ request('category') === $category->slug ? 'text-amber-400 font-semibold' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
                <a href="{{ route('orders.track') }}"
                   class="text-sm font-medium text-zinc-400 hover:text-amber-400 transition-colors {{ request()->routeIs('orders.track') ? 'text-amber-400 font-semibold' : '' }}">
                    Shipment Tracking
                </a>
            </div>

            <!-- Right: Search, Wishlist, Cart, Account -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Search Button -->
                <button type="button"
                        @click="searchOpen = true"
                        class="p-2 text-zinc-400 hover:text-white transition flex items-center gap-2 rounded-lg hover:bg-zinc-900/60"
                        title="Search Products">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="hidden xl:inline text-xs text-zinc-500 border border-zinc-800 bg-zinc-900 px-1.5 py-0.5 rounded font-mono">⌘K</span>
                </button>

                <!-- Wishlist -->
                @php
                    $wishlistCount = app(\App\Services\WishlistService::class)->count();
                @endphp
                <a href="{{ route('wishlist.index') }}"
                   class="relative p-2 text-zinc-400 hover:text-amber-400 transition rounded-lg hover:bg-zinc-900/60"
                   title="View Wishlist">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-zinc-950">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                <!-- Shopping Bag Trigger -->
                <button type="button"
                        @click="$store.cart.open()"
                        class="relative flex items-center gap-2 rounded-xl border border-zinc-800 bg-zinc-900/80 px-3.5 py-2 text-zinc-200 hover:border-amber-500/50 hover:bg-zinc-800 transition shadow-sm group">
                    <svg class="h-5 w-5 text-amber-500 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-xs font-semibold hidden sm:inline">Bag</span>
                    <span class="flex h-5 min-w-[1.25rem] px-1 items-center justify-center rounded-full bg-amber-500 text-[11px] font-black text-zinc-950"
                          x-text="$store.cart.itemsCount"
                          x-show="$store.cart.itemsCount > 0">
                    </span>
                </button>

                <!-- Customer Account Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    @auth
                        <button type="button"
                                @click="open = !open"
                                class="flex items-center gap-2 rounded-xl border border-zinc-800 bg-zinc-900 p-1.5 hover:border-zinc-700 transition">
                            <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="h-7 w-7 rounded-lg object-cover ring-1 ring-amber-500/30">
                            <span class="hidden md:inline text-xs font-medium text-zinc-200 pr-1 max-w-[90px] truncate">
                                {{ auth()->user()->name }}
                            </span>
                        </button>
                    @else
                        <button type="button"
                                @click="open = !open"
                                class="p-2 text-zinc-400 hover:text-white transition rounded-lg hover:bg-zinc-900/60"
                                title="Sign In">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>
                    @endauth

                    <!-- Dropdown Panel -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-2xl border border-zinc-800 bg-zinc-900/95 p-2 shadow-2xl backdrop-blur-xl z-50 text-xs">
                        @auth
                            <div class="px-3 py-2 border-b border-zinc-800 mb-1">
                                <p class="font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ url('/admin') }}"
                                   class="flex items-center gap-2 rounded-lg px-3 py-2 text-amber-400 hover:bg-amber-500/10 transition font-medium">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Filament Admin Panel
                                </a>
                            @endif
                            <a href="{{ route('account.index') }}"
                               class="flex items-center gap-2 rounded-lg px-3 py-2 text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Account Dashboard
                            </a>
                            <a href="{{ route('orders.track') }}"
                               class="flex items-center gap-2 rounded-lg px-3 py-2 text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Track Shipments
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1 border-t border-zinc-800 pt-1">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-rose-400 hover:bg-rose-500/10 transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        @else
                            <div class="px-3 py-2 border-b border-zinc-800 mb-1">
                                <p class="font-semibold text-white">Zyricz Concierge</p>
                                <p class="text-[11px] text-zinc-400">Sign in to manage orders & wishlist</p>
                            </div>
                            <a href="{{ route('login') }}"
                               class="flex items-center gap-2 rounded-lg px-3 py-2 text-white bg-amber-500/20 text-amber-300 font-semibold hover:bg-amber-500/30 transition mb-1">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                               class="flex items-center gap-2 rounded-lg px-3 py-2 text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                                Register An Account
                            </a>
                            <!-- Instant Demo Logins -->
                            <div class="mt-2 pt-2 border-t border-zinc-800/80">
                                <span class="px-3 text-[10px] font-semibold uppercase tracking-wider text-zinc-500">Instant Demo Login</span>
                                <div class="mt-1 flex flex-col gap-1">
                                    <a href="{{ route('demo.login', ['role' => 'admin']) }}"
                                       class="flex items-center justify-between rounded-lg px-3 py-1.5 text-zinc-300 hover:bg-amber-500/10 hover:text-amber-400 transition text-[11px]">
                                        <span>👑 Admin Concierge</span>
                                        <span class="text-[10px] text-zinc-500">Auto-fill &rarr;</span>
                                    </a>
                                    <a href="{{ route('demo.login', ['role' => 'customer']) }}"
                                       class="flex items-center justify-between rounded-lg px-3 py-1.5 text-zinc-300 hover:bg-amber-500/10 hover:text-amber-400 transition text-[11px]">
                                        <span>💎 Elena (Customer)</span>
                                        <span class="text-[10px] text-zinc-500">Auto-fill &rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Slide-Down -->
        <div x-show="mobileMenu"
             x-collapse
             class="lg:hidden border-t border-zinc-800/80 py-4 space-y-2">
            <a href="{{ route('products.index') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-200 hover:bg-zinc-900 hover:text-amber-400">
                All Collections
            </a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-300 hover:bg-zinc-900 hover:text-amber-400">
                    {{ $category->name }}
                </a>
            @endforeach
            <a href="{{ route('orders.track') }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium text-zinc-400 hover:bg-zinc-900 hover:text-amber-400">
                Track Shipment
            </a>
        </div>
    </nav>

    <!-- Search Modal Overlay -->
    <div x-show="searchOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-start justify-center pt-20 px-4"
         @keydown.escape.window="searchOpen = false"
         style="display: none;">
        <div class="w-full max-w-2xl rounded-2xl border border-zinc-800 bg-zinc-900 p-4 shadow-2xl"
             @click.outside="searchOpen = false">
            <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                <svg class="absolute left-4 h-5 w-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text"
                       name="q"
                       autofocus
                       placeholder="Search timepieces, titanium, headphones, folios..."
                       class="w-full rounded-xl border border-zinc-700 bg-zinc-950 py-3.5 pl-12 pr-24 text-sm text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                <button type="submit"
                        class="absolute right-2 rounded-lg bg-amber-500 px-3.5 py-1.5 text-xs font-bold text-zinc-950 hover:bg-amber-400 transition">
                    Search
                </button>
            </form>

            <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-zinc-400">
                <span class="text-zinc-500">Trending Queries:</span>
                <a href="{{ route('products.index', ['q' => 'titanium']) }}" class="rounded-full bg-zinc-800 px-3 py-1 hover:text-amber-400 transition">Titanium</a>
                <a href="{{ route('products.index', ['q' => 'headphones']) }}" class="rounded-full bg-zinc-800 px-3 py-1 hover:text-amber-400 transition">Headphones</a>
                <a href="{{ route('products.index', ['q' => 'automatic']) }}" class="rounded-full bg-zinc-800 px-3 py-1 hover:text-amber-400 transition">Automatic Watch</a>
                <a href="{{ route('products.index', ['q' => 'leather']) }}" class="rounded-full bg-zinc-800 px-3 py-1 hover:text-amber-400 transition">Italian Leather</a>
            </div>
        </div>
    </div>
</header>
