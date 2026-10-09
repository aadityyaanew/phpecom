<div x-show="$store.cart.isOpen"
     x-cloak
     class="relative z-50"
     aria-labelledby="slide-over-title"
     role="dialog"
     aria-modal="true"
     style="display: none;">

    <!-- Backdrop -->
    <div x-show="$store.cart.isOpen"
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity"
         @click="$store.cart.close()"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="$store.cart.isOpen"
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col bg-zinc-900 border-l border-zinc-800 shadow-2xl">

                        <!-- Header -->
                        <div class="p-6 border-b border-zinc-800/80">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-bold text-white tracking-wide uppercase">Your Bag</h2>
                                    <span class="rounded-full bg-amber-500/20 px-2 py-0.5 text-xs font-bold text-amber-400 font-mono"
                                          x-text="$store.cart.itemsCount + ' items'"></span>
                                </div>
                                <button type="button"
                                        @click="$store.cart.close()"
                                        class="rounded-lg p-1.5 text-zinc-400 hover:text-white hover:bg-zinc-800 transition">
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Free Shipping Progress -->
                            <div class="mt-4 rounded-xl border border-zinc-800 bg-zinc-950/60 p-3">
                                <div class="flex items-center justify-between text-xs">
                                    <template x-if="$store.cart.qualifiesForFreeShipping">
                                        <span class="text-emerald-400 font-semibold flex items-center gap-1.5">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Unlocked Free Express Courier
                                        </span>
                                    </template>
                                    <template x-if="!$store.cart.qualifiesForFreeShipping">
                                        <span class="text-zinc-400">
                                            Add <strong class="text-amber-400 font-mono" x-text="'$' + Number($store.cart.amountForFreeShipping).toFixed(2)"></strong> for Free Shipping
                                        </span>
                                    </template>
                                    <span class="text-[11px] text-zinc-500 font-mono" x-text="$store.cart.freeShippingProgress + '%'"></span>
                                </div>
                                <div class="mt-2 h-1.5 w-full rounded-full bg-zinc-800 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-500 to-amber-300 transition-all duration-500"
                                         :style="'width: ' + $store.cart.freeShippingProgress + '%'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Items -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-4">
                            <!-- Empty State -->
                            <template x-if="$store.cart.items.length === 0">
                                <div class="py-16 text-center space-y-4">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-800/80 text-zinc-500">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-base font-semibold text-zinc-200">Your bag is empty</h3>
                                        <p class="text-xs text-zinc-500">Explore our timepieces, acoustics, and curated essentials.</p>
                                    </div>
                                    <a href="{{ route('products.index') }}"
                                       @click="$store.cart.close()"
                                       class="inline-flex rounded-xl bg-amber-500 px-5 py-2.5 text-xs font-bold text-zinc-950 uppercase tracking-wider hover:bg-amber-400 transition">
                                        Browse Atelier
                                    </a>
                                </div>
                            </template>

                            <!-- Items List -->
                            <template x-for="item in $store.cart.items" :key="item.key">
                                <div class="flex gap-4 rounded-xl border border-zinc-800/80 bg-zinc-950/40 p-3 hover:border-zinc-700/80 transition">
                                    <img :src="item.image"
                                         :alt="item.name"
                                         class="h-20 w-20 flex-shrink-0 rounded-lg object-cover bg-zinc-900 ring-1 ring-zinc-800">
                                    <div class="flex flex-1 flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-2">
                                                <h4 class="text-xs font-semibold text-white line-clamp-1" x-text="item.name"></h4>
                                                <button type="button"
                                                        @click="$store.cart.removeItem(item.key)"
                                                        class="text-zinc-500 hover:text-rose-400 transition"
                                                        title="Remove item">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <template x-if="item.variant_name">
                                                <p class="text-[11px] text-zinc-400" x-text="item.variant_name"></p>
                                            </template>
                                            <p class="mt-1 text-xs font-mono font-bold text-amber-400" x-text="'$' + Number(item.price).toFixed(2)"></p>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between">
                                            <!-- Qty Controls -->
                                            <div class="flex items-center rounded-lg border border-zinc-800 bg-zinc-900">
                                                <button type="button"
                                                        @click="$store.cart.updateQty(item.key, item.quantity - 1)"
                                                        class="px-2 py-1 text-zinc-400 hover:text-white transition">
                                                    -
                                                </button>
                                                <span class="px-2 text-xs font-mono text-white" x-text="item.quantity"></span>
                                                <button type="button"
                                                        @click="$store.cart.updateQty(item.key, item.quantity + 1)"
                                                        class="px-2 py-1 text-zinc-400 hover:text-white transition">
                                                    +
                                                </button>
                                            </div>
                                            <span class="text-xs font-mono font-bold text-zinc-200"
                                                  x-text="'$' + Number(item.total).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer Checkout / Coupon / Totals -->
                        <div x-show="$store.cart.items.length > 0" class="border-t border-zinc-800 p-6 space-y-4 bg-zinc-950/80">
                            <!-- Coupon Form -->
                            <div class="space-y-1.5" x-data="{ code: '' }">
                                <div class="flex gap-2">
                                    <input type="text"
                                           x-model="code"
                                           :placeholder="$store.cart.couponCode || 'Promo code (WELCOME10)'"
                                           class="flex-1 rounded-xl border border-zinc-800 bg-zinc-900 px-3 py-2 text-xs uppercase text-white placeholder-zinc-500 focus:border-amber-500 focus:outline-none">
                                    <template x-if="!$store.cart.couponCode">
                                        <button type="button"
                                                @click="$store.cart.applyCoupon(code); code='';"
                                                class="rounded-xl bg-zinc-800 px-3.5 py-2 text-xs font-semibold text-zinc-200 hover:bg-zinc-700 transition">
                                            Apply
                                        </button>
                                    </template>
                                    <template x-if="$store.cart.couponCode">
                                        <button type="button"
                                                @click="$store.cart.removeCoupon()"
                                                class="rounded-xl bg-rose-500/20 px-3.5 py-2 text-xs font-semibold text-rose-400 hover:bg-rose-500/30 transition">
                                            Remove
                                        </button>
                                    </template>
                                </div>
                                <template x-if="$store.cart.couponCode">
                                    <p class="text-[11px] text-emerald-400 flex items-center gap-1">
                                        <span>✓ Code</span>
                                        <span class="font-mono font-bold" x-text="$store.cart.couponCode"></span>
                                        <span>applied</span>
                                    </p>
                                </template>
                            </div>

                            <!-- Calculations Breakdown -->
                            <div class="space-y-1.5 text-xs text-zinc-400 border-t border-zinc-800/80 pt-3">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.subtotal).toFixed(2)"></span>
                                </div>
                                <template x-if="$store.cart.discount > 0">
                                    <div class="flex justify-between text-emerald-400">
                                        <span>Promotion Discount</span>
                                        <span class="font-mono" x-text="'-$' + Number($store.cart.discount).toFixed(2)"></span>
                                    </div>
                                </template>
                                <div class="flex justify-between">
                                    <span>Shipping</span>
                                    <span class="font-mono"
                                          x-text="$store.cart.shipping === 0 ? 'Complimentary' : '$' + Number($store.cart.shipping).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Estimated Sales Tax</span>
                                    <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.tax).toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between border-t border-zinc-800 pt-2 text-sm font-bold text-white">
                                    <span>Total</span>
                                    <span class="font-mono text-base text-amber-400" x-text="'$' + Number($store.cart.total).toFixed(2)"></span>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="space-y-2 pt-2">
                                <a href="{{ route('checkout.index') }}"
                                   @click="$store.cart.close()"
                                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 py-3 text-xs font-bold uppercase tracking-wider text-zinc-950 shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-amber-500 transition">
                                    <span>Proceed to Secure Checkout</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                                <a href="{{ route('cart.index') }}"
                                   @click="$store.cart.close()"
                                   class="flex w-full items-center justify-center rounded-xl border border-zinc-800 py-2.5 text-xs font-semibold text-zinc-300 hover:bg-zinc-800 hover:text-white transition">
                                    View Detailed Bag
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
