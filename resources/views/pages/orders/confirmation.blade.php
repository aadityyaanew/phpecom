<x-layout title="Order Confirmed - {{ $order->order_number }}">
    <div class="bg-zinc-950 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Confirmation Hero Header -->
            <div class="text-center space-y-4">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 shadow-xl shadow-emerald-500/10">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-widest text-amber-500 font-bold">Transaction Confirmed</span>
                    <h1 class="text-3xl sm:text-4xl font-black text-white">Thank You For Your Allocation</h1>
                    <p class="text-xs sm:text-sm text-zinc-400 max-w-lg mx-auto">
                        An official courier receipt has been transmitted to <strong class="text-white">{{ $order->customer_email }}</strong>.
                    </p>
                </div>

                <!-- Reference Number Pill -->
                <div class="inline-flex items-center gap-3 rounded-2xl border border-zinc-800 bg-zinc-900 px-5 py-2.5">
                    <span class="text-xs text-zinc-400">Order Reference:</span>
                    <span class="font-mono text-sm font-black text-amber-400">{{ $order->order_number }}</span>
                </div>
            </div>

            <!-- Shipment Details Card -->
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-800 gap-4">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-white">Shipment Status</h2>
                        <p class="text-xs text-zinc-400 mt-0.5">Assigned to {{ $order->carrier ?? 'Insured Express Courier' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'email' => $order->customer_email]) }}"
                           class="rounded-xl bg-amber-500 px-4 py-2 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                            Track Live Timeline &rarr;
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                    <div>
                        <span class="text-zinc-500 uppercase tracking-wider font-semibold">Estimated Delivery</span>
                        <p class="font-bold text-white text-sm mt-1">
                            {{ $order->estimated_delivery ? $order->estimated_delivery->format('l, F j, Y') : now()->addDays(4)->format('l, F j, Y') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-zinc-500 uppercase tracking-wider font-semibold">Tracking Number</span>
                        <p class="font-mono font-bold text-amber-400 text-sm mt-1">
                            {{ $order->tracking_number ?? 'Pending Dispatch' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-zinc-500 uppercase tracking-wider font-semibold">Payment Method</span>
                        <p class="font-bold text-white text-sm mt-1 uppercase">
                            {{ $order->payment_method === 'card' ? 'Credit Card (Stripe)' : strtoupper($order->payment_method) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Itemized Receipt Card -->
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-6">
                <h2 class="text-sm font-bold uppercase tracking-wider text-white pb-3 border-b border-zinc-800">
                    Itemized Allocation
                </h2>

                <div class="divide-y divide-zinc-800/80">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between gap-4 text-xs">
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->product_image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&auto=format&fit=crop&q=80' }}"
                                     alt="{{ $item->product_name }}"
                                     class="h-16 w-16 rounded-xl object-cover bg-zinc-950 ring-1 ring-zinc-800 shrink-0">
                                <div class="space-y-0.5">
                                    <h3 class="font-bold text-white text-sm">{{ $item->product_name }}</h3>
                                    @if($item->variant_details && isset($item->variant_details['variant']))
                                        <p class="text-amber-500 font-semibold">{{ $item->variant_details['variant'] }}</p>
                                    @endif
                                    <p class="font-mono text-zinc-500 text-[11px]">SKU: {{ $item->product_sku }} &bull; Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-white text-sm">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Breakdown -->
                <div class="border-t border-zinc-800 pt-4 space-y-2 text-xs text-zinc-400">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-mono text-zinc-200">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-400">
                            <span>Promotional Discount ({{ $order->coupon_code }})</span>
                            <span class="font-mono font-bold">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Courier Transit ({{ ucfirst($order->shipping_method) }})</span>
                        <span class="font-mono text-zinc-200">
                            {{ $order->shipping_rate == 0 ? 'Complimentary' : '$' . number_format($order->shipping_rate, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sales Tax</span>
                        <span class="font-mono text-zinc-200">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-zinc-800 pt-3 text-base font-black text-white">
                        <span>Total Paid</span>
                        <span class="font-mono text-xl text-amber-400">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <!-- Addresses Row -->
                <div class="border-t border-zinc-800 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-zinc-400">
                    <div>
                        <span class="font-bold text-white uppercase tracking-wider block mb-1">Shipping Destination</span>
                        <p class="text-zinc-300 font-semibold">{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['address_line_2']))
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['country'] ?? 'United States' }}</p>
                    </div>
                    <div>
                        <span class="font-bold text-white uppercase tracking-wider block mb-1">Concierge Client Note</span>
                        <p class="italic text-zinc-400">{{ $order->customer_notes ?? 'No special delivery instructions provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-xs font-bold uppercase tracking-wider">
                <button type="button"
                        onclick="window.print()"
                        class="w-full sm:w-auto rounded-xl border border-zinc-800 bg-zinc-900 px-6 py-3.5 text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                    Print Official Receipt
                </button>
                <a href="{{ route('products.index') }}"
                   class="w-full sm:w-auto rounded-xl bg-amber-500 px-8 py-3.5 text-zinc-950 hover:bg-amber-400 transition text-center shadow-lg shadow-amber-500/20">
                    Return to Atelier Catalog
                </a>
            </div>

        </div>
    </div>
</x-layout>
