<footer class="border-t border-zinc-800 bg-zinc-950 text-zinc-400">
    <!-- Value Propositions Banner -->
    <div class="border-b border-zinc-800/80 bg-zinc-900/40 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Atelier Authenticity</h4>
                        <p class="mt-0.5 text-xs text-zinc-500">Every piece verified & serialized.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Global Express Courier</h4>
                        <p class="mt-0.5 text-xs text-zinc-500">Fast insured delivery with live tracking.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">30-Day Atelier Trial</h4>
                        <p class="mt-0.5 text-xs text-zinc-500">Complimentary returns on all items.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">24/7 VIP Concierge</h4>
                        <p class="mt-0.5 text-xs text-zinc-500">Dedicated personal support staff.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Body -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-zinc-950 font-black">
                        Z
                    </div>
                    <span class="text-lg font-black tracking-[0.25em] text-white uppercase">
                        ZYRICZ
                    </span>
                </div>
                <p class="text-xs text-zinc-400 leading-relaxed max-w-sm">
                    Atelier of modern precision horology, reference acoustics, and handcrafted leather carry. Built without compromise using aerospace-grade metals and meticulous attention to tactile sensation.
                </p>
                <div class="pt-2 text-xs text-zinc-500 space-y-1">
                    <p>Concierge: <a href="mailto:concierge@zyricz.com" class="text-amber-400 hover:underline">concierge@zyricz.com</a></p>
                    <p>Direct Inquiries: <span class="text-zinc-300 font-mono">+1 (800) 997-4299</span></p>
                </div>
            </div>

            <!-- Collections -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Curations</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('products.index') }}" class="hover:text-amber-400 transition">All Products</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'luxury-timepieces']) }}" class="hover:text-amber-400 transition">Automatic Horology</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'audio-acoustics']) }}" class="hover:text-amber-400 transition">Reference Audio</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'leather-carry']) }}" class="hover:text-amber-400 transition">Italian Leather</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'smart-workspace']) }}" class="hover:text-amber-400 transition">Tactile Workspace</a></li>
                </ul>
            </div>

            <!-- Client Services -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Client Support</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="{{ route('orders.track') }}" class="hover:text-amber-400 transition">Track Your Order</a></li>
                    <li><a href="{{ route('wishlist.index') }}" class="hover:text-amber-400 transition">Saved Wishlist</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-amber-400 transition">Shopping Bag</a></li>
                    <li><a href="{{ route('account.index') }}" class="hover:text-amber-400 transition">Account Portal</a></li>
                    <li><a href="{{ url('/admin') }}" class="text-amber-400 hover:underline font-semibold">Filament Admin Panel &rarr;</a></li>
                </ul>
            </div>

            <!-- Newsletter Subscription -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-white mb-2">The Private List</h4>
                <p class="text-xs text-zinc-400 mb-4">
                    Subscribe for exclusive drops, artisan interviews, and 10% off your initial purchase.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="email"
                           name="email"
                           required
                           placeholder="Enter your email"
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-3.5 py-2.5 text-xs text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none">
                    <button type="submit"
                            class="w-full rounded-xl bg-amber-500 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                        Join The Atelier
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright & Badges -->
        <div class="mt-12 pt-8 border-t border-zinc-800/80 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-zinc-500">
            <p>&copy; {{ date('Y') }} ZYRICZ Atelier Inc. All rights reserved. Precision engineered.</p>
            <div class="flex items-center gap-4 text-zinc-400 text-xs">
                <span>Encrypted 256-Bit SSL</span>
                <span>•</span>
                <span>Stripe Verified</span>
                <span>•</span>
                <span>Global Express</span>
            </div>
        </div>
    </div>
</footer>
