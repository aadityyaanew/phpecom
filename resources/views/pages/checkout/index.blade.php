<x-layout title="Secure Atelier Checkout">
    <div class="bg-zinc-950 border-b border-zinc-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs text-zinc-500 mb-1">
                    <a href="{{ route('cart.index') }}" class="hover:text-zinc-300">Bag</a>
                    <span>/</span>
                    <span class="text-amber-400">Checkout</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Express Checkout</h1>
            </div>
            <div class="flex items-center gap-2 text-xs text-zinc-400">
                <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>256-Bit SSL Encrypted Session</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
         x-data="{
            shippingMethod: '{{ $summary['shipping_method'] }}',
            paymentMethod: 'card',
            cardNumber: '4242 •••• •••• 4242',
            cardExpiry: '12/28',
            cardCvc: '884',
            submitting: false,
            async selectShipping(method) {
                this.shippingMethod = method;
                const token = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
                const res = await fetch('{{ route('checkout.shipping') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ shipping_method: method })
                });
                const data = await res.json();
                if (data.success) {
                    $store.cart.updateState(data.summary);
                }
            },
            fillTestCard() {
                this.cardNumber = '4242 4242 4242 4242';
                this.cardExpiry = '08/29';
                this.cardCvc = '314';
                Alpine.store('toast').add('Stripe test card autofilled.', 'info');
            }
         }">

        <form action="{{ route('checkout.process') }}"
              method="POST"
              @submit="submitting = true"
              class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            @csrf

            <!-- Left: Checkout Forms -->
            <div class="lg:col-span-7 space-y-10">

                <!-- 1. Customer Contact -->
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-amber-500">1. Client Contact</h2>
                        @guest
                            <a href="{{ route('login') }}" class="text-xs text-zinc-400 hover:text-white underline">
                                Have an account? Sign in
                            </a>
                        @endguest
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Email Address *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user?->email ?? $defaultAddress?->email) }}"
                                   required
                                   placeholder="elena@example.com"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            @error('email') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Mobile Phone (Delivery SMS)</label>
                            <input type="tel"
                                   name="phone"
                                   value="{{ old('phone', $user?->phone ?? $defaultAddress?->phone) }}"
                                   placeholder="+1 (555) 019-2834"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- 2. Shipping Address -->
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-amber-500 pb-3 border-b border-zinc-800">
                        2. Shipping Destination
                    </h2>

                    <div class="space-y-4 text-xs">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">First Name *</label>
                                <input type="text"
                                       name="first_name"
                                       value="{{ old('first_name', $defaultAddress?->first_name ?? ($user ? explode(' ', $user->name)[0] : 'Elena')) }}"
                                       required
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Last Name *</label>
                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name', $defaultAddress?->last_name ?? 'Rostova') }}"
                                       required
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Street Address *</label>
                            <input type="text"
                                   name="address_line_1"
                                   value="{{ old('address_line_1', $defaultAddress?->address_line_1 ?? '742 Evergreen Terrace') }}"
                                   required
                                   placeholder="House number and street name"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Apartment / Suite / Floor (Optional)</label>
                            <input type="text"
                                   name="address_line_2"
                                   value="{{ old('address_line_2', $defaultAddress?->address_line_2 ?? 'Suite 400') }}"
                                   placeholder="Apt, Suite, Penthouse"
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">City *</label>
                                <input type="text"
                                       name="city"
                                       value="{{ old('city', $defaultAddress?->city ?? 'San Francisco') }}"
                                       required
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">State / Province</label>
                                <input type="text"
                                       name="state"
                                       value="{{ old('state', $defaultAddress?->state ?? 'CA') }}"
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Postal / ZIP Code *</label>
                                <input type="text"
                                       name="postal_code"
                                       value="{{ old('postal_code', $defaultAddress?->postal_code ?? '94107') }}"
                                       required
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Country *</label>
                            <select name="country" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white focus:border-amber-500 focus:outline-none">
                                <option value="United States" selected>United States</option>
                                <option value="Canada">Canada</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Japan">Japan</option>
                                <option value="Australia">Australia</option>
                            </select>
                        </div>

                        <label class="flex items-center gap-2 pt-2 text-zinc-400 cursor-pointer">
                            <input type="checkbox" name="same_as_shipping" value="1" checked class="rounded border-zinc-800 bg-zinc-950 text-amber-500 focus:ring-0">
                            <span>Billing address is identical to shipping destination</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Delivery Method -->
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-amber-500 pb-3 border-b border-zinc-800">
                        3. Logistics & Delivery Service
                    </h2>

                    <div class="space-y-3 text-xs">
                        <!-- Standard Ground -->
                        <label @click="selectShipping('standard')"
                               class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'standard' ? 'border-amber-500 bg-amber-500/10 ring-1 ring-amber-500' : 'border-zinc-800 bg-zinc-950 hover:border-zinc-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method_radio" value="standard" :checked="shippingMethod === 'standard'" class="text-amber-500 focus:ring-0">
                                <div>
                                    <p class="font-bold text-white">FedEx Ground Insured (3-5 Business Days)</p>
                                    <p class="text-zinc-500 text-[11px]">Tracked and signed package with tamper seal.</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-white">
                                {{ $summary['subtotal'] >= $summary['free_shipping_threshold'] ? 'FREE' : '$12.00' }}
                            </span>
                        </label>

                        <!-- Express Air -->
                        <label @click="selectShipping('express')"
                               class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'express' ? 'border-amber-500 bg-amber-500/10 ring-1 ring-amber-500' : 'border-zinc-800 bg-zinc-950 hover:border-zinc-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method_radio" value="express" :checked="shippingMethod === 'express'" class="text-amber-500 focus:ring-0">
                                <div>
                                    <p class="font-bold text-white">DHL Express Priority (1-2 Business Days)</p>
                                    <p class="text-zinc-500 text-[11px]">Express air transit with dedicated flight routing.</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-white">$28.00</span>
                        </label>

                        <!-- White Glove Overnight -->
                        <label @click="selectShipping('priority')"
                               class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                               :class="shippingMethod === 'priority' ? 'border-amber-500 bg-amber-500/10 ring-1 ring-amber-500' : 'border-zinc-800 bg-zinc-950 hover:border-zinc-700'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="shipping_method_radio" value="priority" :checked="shippingMethod === 'priority'" class="text-amber-500 focus:ring-0">
                                <div>
                                    <p class="font-bold text-white">White Glove Overnight Priority (Next Business Morning)</p>
                                    <p class="text-zinc-500 text-[11px]">Direct courier handoff requiring authenticated ID.</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-white">$45.00</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Payment Gateway -->
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/60 p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-800">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-amber-500">4. Payment Gateway</h2>
                        <span class="rounded bg-zinc-800 px-2 py-0.5 text-[10px] font-mono text-zinc-400">Stripe Sandbox / Mock Active</span>
                    </div>

                    <!-- Payment Method Tabs -->
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <button type="button"
                                @click="paymentMethod = 'card'"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border transition"
                                :class="paymentMethod === 'card' ? 'border-amber-500 bg-amber-500/15 text-white font-bold' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:text-white'">
                            <span>Credit Card</span>
                            <span class="text-[10px] text-zinc-500">Stripe Secure</span>
                        </button>
                        <button type="button"
                                @click="paymentMethod = 'paypal'"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border transition"
                                :class="paymentMethod === 'paypal' ? 'border-amber-500 bg-amber-500/15 text-white font-bold' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:text-white'">
                            <span>PayPal Express</span>
                            <span class="text-[10px] text-zinc-500">One-Touch</span>
                        </button>
                        <button type="button"
                                @click="paymentMethod = 'cod'"
                                class="flex flex-col items-center justify-center p-3 rounded-xl border transition"
                                :class="paymentMethod === 'cod' ? 'border-amber-500 bg-amber-500/15 text-white font-bold' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:text-white'">
                            <span>Cash on Hand</span>
                            <span class="text-[10px] text-zinc-500">Direct Delivery</span>
                        </button>
                    </div>

                    <input type="hidden" name="payment_method" :value="paymentMethod">

                    <!-- Credit Card Sub-form -->
                    <div x-show="paymentMethod === 'card'" class="space-y-4 rounded-2xl border border-zinc-800/80 bg-zinc-950 p-5 text-xs">
                        <div class="flex items-center justify-between pb-2 border-b border-zinc-900">
                            <span class="font-bold text-zinc-300">Card Details</span>
                            <button type="button"
                                    @click="fillTestCard()"
                                    class="text-[11px] font-semibold text-amber-400 hover:underline">
                                Autofill Test Card &rarr;
                            </button>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">Card Number *</label>
                            <input type="text"
                                   name="card_number"
                                   x-model="cardNumber"
                                   required
                                   class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-2.5 font-mono text-white focus:border-amber-500 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-400 mb-1">Expiration Date (MM/YY) *</label>
                                <input type="text"
                                       name="card_expiry"
                                       x-model="cardExpiry"
                                       placeholder="MM/YY"
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-2.5 font-mono text-white focus:border-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-400 mb-1">CVC Security Code *</label>
                                <input type="text"
                                       name="card_cvc"
                                       x-model="cardCvc"
                                       placeholder="123"
                                       class="w-full rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-2.5 font-mono text-white focus:border-amber-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- PayPal / COD Notes -->
                    <div x-show="paymentMethod === 'paypal'" class="p-4 rounded-2xl border border-blue-500/30 bg-blue-500/10 text-xs text-blue-300">
                        You will be simulated into the verified PayPal Instant Checkout channel upon submitting your order.
                    </div>
                    <div x-show="paymentMethod === 'cod'" class="p-4 rounded-2xl border border-amber-500/30 bg-amber-500/10 text-xs text-amber-300">
                        Settlement in cash or certified cheque directly with the courier upon physical receipt.
                    </div>

                    <!-- Notes -->
                    <div class="text-xs">
                        <label class="block font-bold text-zinc-400 mb-1 uppercase tracking-wider">Delivery Instructions for Courier (Optional)</label>
                        <textarea name="notes"
                                  rows="2"
                                  placeholder="e.g. Leave with residential concierge, gate code #4092"
                                  class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none"></textarea>
                    </div>
                </div>

            </div>

            <!-- Right: Order Summary Sticky Card -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 rounded-3xl border border-zinc-800 bg-zinc-900/80 p-6 sm:p-8 space-y-6 shadow-2xl backdrop-blur-xl">
                    <h2 class="text-base font-bold text-white uppercase tracking-wider">Order Recap</h2>

                    <!-- Line Items List -->
                    <div class="max-h-72 overflow-y-auto divide-y divide-zinc-800/80 pr-1 space-y-3">
                        <template x-for="item in $store.cart.items" :key="item.key">
                            <div class="pt-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <img :src="item.image" :alt="item.name" class="h-14 w-14 rounded-xl object-cover bg-zinc-950 ring-1 ring-zinc-800 shrink-0">
                                    <div>
                                        <h4 class="font-bold text-white line-clamp-1" x-text="item.name"></h4>
                                        <p class="text-[11px] text-zinc-500" x-text="'Qty: ' + item.quantity"></p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-amber-400 shrink-0" x-text="'$' + Number(item.total).toFixed(2)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-2 text-xs text-zinc-400 border-t border-zinc-800 pt-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.subtotal).toFixed(2)"></span>
                        </div>
                        <template x-if="$store.cart.discount > 0">
                            <div class="flex justify-between text-emerald-400">
                                <span>Promotional Discount</span>
                                <span class="font-mono font-bold" x-text="'-$' + Number($store.cart.discount).toFixed(2)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between">
                            <span>Courier Transit</span>
                            <span class="font-mono"
                                  x-text="$store.cart.shipping === 0 ? 'Complimentary' : '$' + Number($store.cart.shipping).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sales Tax (8.5%)</span>
                            <span class="font-mono text-zinc-200" x-text="'$' + Number($store.cart.tax).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between border-t border-zinc-800 pt-3 text-base font-black text-white">
                            <span>Final Total Due</span>
                            <span class="font-mono text-xl text-amber-400" x-text="'$' + Number($store.cart.total).toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Place Order CTA Button -->
                    <button type="submit"
                            :disabled="submitting"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 py-4 text-xs font-black uppercase tracking-widest text-zinc-950 shadow-xl shadow-amber-500/25 hover:from-amber-300 hover:to-amber-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <span>Authorize & Place Order</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <span class="h-4 w-4 border-2 border-zinc-950 border-t-transparent rounded-full animate-spin"></span>
                                <span>Securing Order Allocation...</span>
                            </span>
                        </template>
                    </button>

                    <div class="pt-1 text-center text-[11px] text-zinc-500 space-y-1">
                        <p>🔒 Level 1 PCI-DSS Compliant Transaction</p>
                        <p>Automated Order Confirmation & Tracking Dispatched</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout>
