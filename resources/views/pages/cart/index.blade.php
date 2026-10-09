<x-layout title="Your Atelier Bag">
    <div class="bg-zinc-950 border-b border-zinc-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-black text-white tracking-tight">Your Atelier Bag</h1>
            <p class="text-xs text-zinc-400 mt-1">Review selected horology, acoustics, and curated pieces prior to checkout.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data>
        <template x-if="$store.cart.items.length === 0">
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-16 text-center space-y-4 max-w-xl mx-auto">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-zinc-800/80 text-zinc-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-xl font-bold text-white">Your bag is currently vacant</h2>
                    <p class="text-xs text-zinc-400">Discover our collection of timepieces, headphones, and minimal carry.</p>
                </div>
                <a href="{{ route('products.index') }}"
                   class="inline-flex rounded-xl bg-amber-500 px-8 py-3.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                    Explore The Catalog
                </a>
            </div>
        </template>

        <template x-if="$store.cart.items.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Left: Items Table -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Free Shipping Meter -->
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/70 p-4">
                        <div class="flex items-center justify-between text-xs">
                            <template x-if="$store.cart.qualifiesForFreeShipping">
                                <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                                    <span>🎉</span>
                                    <span>You qualify for complimentary express courier dispatch!</span>
                                </span>
                            </template>
                            <template x-if="!$store.cart.qualifiesForFreeShipping">
                                <span class="text-zinc-300">
                                    Add <strong class="text-amber-400 font-mono" x-text="'$' + Number($store.cart.amountForFreeShipping).toFixed(2)"></strong> more for Complimentary Express Shipping
                                </span>
                            </template>
                            <span class="font-mono text-zinc-500 text-xs" x-text="$store.cart.freeShippingProgress + '%'"></span>
                        </div>
                        <div class="mt-2.5 h-2 w-full rounded-full bg-zinc-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-amber-300 transition-all duration-500"
                                 :style="'width: ' + $store.cart.freeShippingProgress + '%'"></div>
                        </div>
                    </div>

                    <!-- Line Items -->
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/40 divide-y divide-zinc-800/80 overflow-hidden">
                        <template x-for="item in $store.cart.items" :key="item.key">
                            <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:bg-zinc-900/60 transition">
                                <div class="flex items-center gap-4">
                                    <img :src="item.image" :alt="item.name" class="h-24 w-24 rounded-2xl object-cover bg-zinc-900 ring-1 ring-zinc-800">
                                    <div class="space-y-1">
                                        <h3 class="text-sm font-bold text-white line-clamp-1" x-text="item.name"></h3>
                                        <template x-if="item.variant_name">
                                            <p class="text-xs text-amber-500/90 font-medium" x-text="item.variant_name"></p>
                                        </template>
                                        <p class="text-[11px] font-mono text-zinc-500" x-text="'SKU: ' + item.sku"></p>
                                        <p class="text-xs font-mono font-bold text-amber-400" x-text="'$' + Number(item.price).toFixed(2)"></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between w-full sm:w-auto gap-6">
                                    <!-- Qty -->
                                    <div class="flex items-center rounded-xl border border-zinc-800 bg-zinc-950 p-1">
                                        <button type="button"
                                                @click="$store.cart.updateQty(item.key, item.quantity - 1)"
                                                class="h-8 w-8 text-zinc-400 hover:text-white flex items-center justify-center font-bold">
                                            -
                                        </button>
                                        <span class="w-10 text-center font-mono text-xs font-bold text-white" x-text="item.quantity"></span>
                                        <button type="button"
                                                @click="$store.cart.updateQty(item.key, item.quantity + 1)"
                                                class="h-8 w-8 text-zinc-400 hover:text-white flex items-center justify-center font-bold">
                                            +
                                        </button>
                                    </div>

                                    <!-- Total & Remove -->
                                    <div class="text-right">
                                        <span class="block font-mono text-sm font-black text-white" x-text="'$' + Number(item.total).toFixed(2)"></span>
                                        <button type="button"
                                                @click="$store.cart.removeItem(item.key)"
                                                class="text-xs text-rose-400 hover:underline mt-1">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Return To Catalog -->
                    <div class="flex items-center justify-between text-xs">
                        <a href="{{ route('products.index') }}" class="text-amber-400 hover:underline font-semibold flex items-center gap-1">
                            &larr; Continue Exploring Collections
                        </a>
                    </div>
                </div>

                <!-- Right: Order Summary Card -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-3xl border border-zinc-800 bg-zinc-900/70 p-6 space-y-6 shadow-xl">
                        <h2 class="text-base font-bold text-white tracking-wide uppercase">Order Summary</h2>

                        <!-- Coupon Input Box -->
                        <div class="space-y-2" x-data="{ couponInput: '' }">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400">Promotional Code</label>
                            <div class="flex gap-2">
                                <input type="text"
                                       x-model="couponInput"
                                       :placeholder="$store.cart.couponCode || 'WELCOME10'"
                                       class="flex-1 rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-xs uppercase text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                                <template x-if="!$store.cart.couponCode">
                                    <button type="button"
                                            @click="$store.cart.applyCoupon(couponInput); couponInput='';"
                                            class="rounded-xl bg-zinc-800 px-4 py-2 text-xs font-bold text-white hover:bg-zinc-700 transition">
                                        Apply
                                    </button>
                                </template>
                                <template x-if="$store.cart.couponCode">
                                    <button type="button"
                                            @click="$store.cart.removeCoupon()"
                                            class="rounded-xl bg-rose-500/20 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-500/30 transition">
                                        Remove
                                    </button>
                                </template>
                            </div>
                            <template x-if="$store.cart.couponCode">
                                <p class="text-[11px] text-emerald-400 flex items-center gap-1">
                                    <span>✓ Code applied:</span>
                                    <strong class="font-mono" x-text="$store.cart.couponCode"></strong>
                                </p>
                            </template>
                        </div>

                        <!-- Calculations -->
                        <div class="space-y-3 text-xs border-t border-zinc-800 pt-4 text-zinc-400">
                            <div class="flex justify-between">
                                <span>Bag Subtotal</span>
                                <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.subtotal).toFixed(2)"></span>
                            </div>
                            <template x-if="$store.cart.discount > 0">
                                <div class="flex justify-between text-emerald-400">
                                    <span>Promotional Savings</span>
                                    <span class="font-mono font-bold" x-text="'-$' + Number($store.cart.discount).toFixed(2)"></span>
                                </div>
                            </template>
                            <div class="flex justify-between">
                                <span>Shipping & Handling</span>
                                <span class="font-mono"
                                      x-text="$store.cart.shipping === 0 ? 'Complimentary' : '$' + Number($store.cart.shipping).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estimated Tax</span>
                                <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.tax).toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between border-t border-zinc-800 pt-3 text-base font-bold text-white">
                                <span>Estimated Total</span>
                                <span class="font-mono text-xl text-amber-400" x-text="'$' + Number($store.cart.total).toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Button -->
                        <a href="{{ route('checkout.index') }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 py-4 text-xs font-bold uppercase tracking-wider text-zinc-950 shadow-xl shadow-amber-500/20 hover:from-amber-300 hover:to-amber-500 transition">
                            <span>Proceed to Checkout</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <div class="pt-2 text-center text-[11px] text-zinc-500 space-y-1">
                            <p>🔒 256-Bit SSL Encrypted Checkout</p>
                            <p>30-Day Atelier Satisfaction Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layout>
