<x-layout title="Live Shipment & Transit Tracking">
    <div class="bg-zinc-950 border-b border-zinc-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-black text-white tracking-tight">Shipment & Logistics Tracking</h1>
            <p class="text-xs text-zinc-400 mt-1">Real-time status updates on your horology and bespoke allocations in transit.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">

        <!-- Tracking Search Form -->
        <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 shadow-xl">
            <form action="{{ route('orders.track') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Order Number *</label>
                        <input type="text"
                               name="order_number"
                               value="{{ request('order_number', 'ZYR-2026-91044') }}"
                               required
                               placeholder="e.g. ZYR-2026-91044"
                               class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 uppercase font-mono text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Account / Contact Email *</label>
                        <input type="email"
                               name="email"
                               value="{{ request('email', 'customer@zyricz.com') }}"
                               required
                               placeholder="customer@zyricz.com"
                               class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] text-zinc-500">
                        Try demo order: <strong class="text-amber-400 font-mono">ZYR-2026-91044</strong> / <strong class="text-amber-400">customer@zyricz.com</strong>
                    </span>
                    <button type="submit"
                            class="rounded-xl bg-amber-500 px-6 py-3 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                        Query Live Status
                    </button>
                </div>
            </form>
        </div>

        @if($searched && !$order)
            <!-- Not Found State -->
            <div class="rounded-3xl border border-rose-500/30 bg-rose-500/10 p-8 text-center space-y-2">
                <h3 class="text-base font-bold text-rose-300">No Matching Order Located</h3>
                <p class="text-xs text-rose-200/80 max-w-md mx-auto">
                    We could not find an order matching that reference number and email combination. Please check your order confirmation receipt or contact concierge.
                </p>
            </div>
        @elseif($order)
            <!-- Found Order Tracking View -->
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-8">
                <!-- Header Status -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-zinc-800 gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-lg font-black text-white">{{ $order->order_number }}</span>
                            <span class="rounded-full bg-{{ $order->status_color }}-500/10 border border-{{ $order->status_color }}-500/30 px-3 py-0.5 text-xs font-bold uppercase text-{{ $order->status_color }}-400">
                                {{ $order->formatted_status }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-1">
                            Courier: <strong class="text-white">{{ $order->carrier ?? 'Dedicated Courier' }}</strong> &bull;
                            Tracking Reference: <strong class="text-amber-400 font-mono">{{ $order->tracking_number ?? 'Pending' }}</strong>
                        </p>
                    </div>

                    <div class="text-right sm:text-right">
                        <span class="text-xs text-zinc-500">Estimated Delivery</span>
                        <p class="text-sm font-bold text-white font-mono">
                            {{ $order->estimated_delivery ? $order->estimated_delivery->format('M d, Y') : 'In Transit' }}
                        </p>
                    </div>
                </div>

                <!-- Visual Step Indicator -->
                @php
                    $steps = [
                        'pending' => 1,
                        'processing' => 2,
                        'shipped' => 3,
                        'delivered' => 4,
                    ];
                    $currentStep = $steps[$order->status] ?? 2;
                @endphp
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-amber-500">Progress Tracker</h3>
                    <div class="grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="space-y-1.5">
                            <div class="h-2 rounded-full {{ $currentStep >= 1 ? 'bg-amber-400' : 'bg-zinc-800' }}"></div>
                            <span class="{{ $currentStep >= 1 ? 'text-white font-bold' : 'text-zinc-600' }} text-[11px]">Placed</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="h-2 rounded-full {{ $currentStep >= 2 ? 'bg-amber-400' : 'bg-zinc-800' }}"></div>
                            <span class="{{ $currentStep >= 2 ? 'text-white font-bold' : 'text-zinc-600' }} text-[11px]">Processing</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="h-2 rounded-full {{ $currentStep >= 3 ? 'bg-amber-400' : 'bg-zinc-800' }}"></div>
                            <span class="{{ $currentStep >= 3 ? 'text-white font-bold' : 'text-zinc-600' }} text-[11px]">In Transit</span>
                        </div>
                        <div class="space-y-1.5">
                            <div class="h-2 rounded-full {{ $currentStep >= 4 ? 'bg-emerald-400' : 'bg-zinc-800' }}"></div>
                            <span class="{{ $currentStep >= 4 ? 'text-emerald-400 font-bold' : 'text-zinc-600' }} text-[11px]">Delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Event Timeline Log -->
                <div class="space-y-4 pt-4 border-t border-zinc-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Logistics Event History</h3>
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-zinc-800">
                        @foreach($order->trackings as $point)
                            <div class="relative group">
                                <span class="absolute -left-6 top-1 h-2.5 w-2.5 rounded-full bg-amber-400 ring-4 ring-zinc-900"></span>
                                <div class="space-y-0.5">
                                    <div class="flex items-baseline justify-between gap-4">
                                        <h4 class="text-xs font-bold text-white">{{ $point->status_title }}</h4>
                                        <span class="font-mono text-[11px] text-zinc-500 shrink-0">
                                            {{ $point->occurred_at->format('M d, H:i') }}
                                        </span>
                                    </div>
                                    @if($point->location)
                                        <p class="text-[11px] text-amber-500/90 font-medium">{{ $point->location }}</p>
                                    @endif
                                    @if($point->description)
                                        <p class="text-xs text-zinc-400">{{ $point->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Package Contents Item List -->
                <div class="space-y-4 pt-4 border-t border-zinc-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-white">Package Contents</h3>
                    <div class="divide-y divide-zinc-800/80">
                        @foreach($order->items as $item)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product_image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&auto=format&fit=crop&q=80' }}"
                                         alt="{{ $item->product_name }}"
                                         class="h-12 w-12 rounded-xl object-cover bg-zinc-950">
                                    <div>
                                        <p class="font-bold text-white">{{ $item->product_name }}</p>
                                        <p class="text-[11px] text-zinc-500">Qty: {{ $item->quantity }} &bull; SKU: {{ $item->product_sku }}</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-white">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-layout>
