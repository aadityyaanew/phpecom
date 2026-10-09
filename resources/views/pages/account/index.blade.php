<x-layout title="Client Account Portal">
    <div class="bg-zinc-950 border-b border-zinc-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}"
                         alt="{{ $user->name }}"
                         class="h-16 w-16 rounded-2xl object-cover ring-2 ring-amber-500/30">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-black text-white">{{ $user->name }}</h1>
                            <span class="rounded-full bg-amber-500/10 border border-amber-500/30 px-2.5 py-0.5 text-[10px] font-bold uppercase text-amber-400">
                                {{ $user->role === 'admin' ? 'Administrator' : 'Client Connoisseur' }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ $user->email }} &bull; Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($user->isAdmin())
                        <a href="{{ url('/admin') }}"
                           class="rounded-xl bg-amber-500 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                            Access Filament Admin &rarr;
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="rounded-xl border border-zinc-800 bg-zinc-900 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-rose-400 hover:bg-zinc-800 transition">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ tab: 'orders' }">
        <!-- Tab Navigation -->
        <div class="flex items-center gap-6 border-b border-zinc-800 pb-4 text-xs font-bold uppercase tracking-wider mb-8">
            <button type="button"
                    @click="tab = 'orders'"
                    :class="tab === 'orders' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                Order History ({{ $orders->total() }})
            </button>
            <button type="button"
                    @click="tab = 'addresses'"
                    :class="tab === 'addresses' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                Saved Destinations ({{ $addresses->count() }})
            </button>
            <button type="button"
                    @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'text-amber-400 border-b-2 border-amber-400 pb-4 -mb-4' : 'text-zinc-500 hover:text-white'">
                Security & Details
            </button>
        </div>

        <!-- 1. Orders Tab -->
        <div x-show="tab === 'orders'" class="space-y-6">
            @if($orders->isEmpty())
                <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-12 text-center space-y-4">
                    <p class="text-sm text-zinc-400">You haven't placed any allocations yet.</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex rounded-xl bg-amber-500 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                        Explore Catalog
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $ord)
                        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6 space-y-4 hover:border-zinc-700 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-800 gap-4 text-xs">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-sm font-bold text-white">{{ $ord->order_number }}</span>
                                        <span class="rounded-full bg-{{ $ord->status_color }}-500/10 border border-{{ $ord->status_color }}-500/30 px-2.5 py-0.5 text-[10px] font-bold uppercase text-{{ $ord->status_color }}-400">
                                            {{ $ord->formatted_status }}
                                        </span>
                                    </div>
                                    <p class="text-zinc-500">Placed on {{ $ord->created_at->format('M d, Y') }} &bull; Total: <strong class="text-amber-400 font-mono">${{ number_format($ord->total, 2) }}</strong></p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('orders.confirmation', $ord->order_number) }}"
                                       class="rounded-xl border border-zinc-800 bg-zinc-900 px-3.5 py-1.5 text-xs font-semibold text-zinc-300 hover:text-white transition">
                                        View Receipt
                                    </a>
                                    <a href="{{ route('orders.track', ['order_number' => $ord->order_number, 'email' => $ord->customer_email]) }}"
                                       class="rounded-xl bg-amber-500/10 border border-amber-500/30 px-3.5 py-1.5 text-xs font-semibold text-amber-400 hover:bg-amber-500/20 transition">
                                        Live Tracking
                                    </a>
                                </div>
                            </div>

                            <!-- Line items summary -->
                            <div class="divide-y divide-zinc-800/60">
                                @foreach($ord->items as $it)
                                    <div class="py-2 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $it->product_image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&auto=format&fit=crop&q=80' }}"
                                                 alt="{{ $it->product_name }}"
                                                 class="h-10 w-10 rounded-lg object-cover bg-zinc-950">
                                            <div>
                                                <p class="font-semibold text-white">{{ $it->product_name }}</p>
                                                <p class="text-[11px] text-zinc-500 font-mono">Qty: {{ $it->quantity }} &bull; ${{ number_format($it->unit_price, 2) }}</p>
                                            </div>
                                        </div>
                                        <span class="font-mono font-bold text-zinc-300">${{ number_format($it->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- 2. Addresses Tab -->
        <div x-show="tab === 'addresses'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $addr)
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-white uppercase">{{ $addr->full_name }}</span>
                            @if($addr->is_default)
                                <span class="rounded bg-amber-500/10 text-amber-400 border border-amber-500/30 px-2 py-0.5 text-[10px] font-bold">Default</span>
                            @endif
                        </div>
                        <p class="text-zinc-300">{{ $addr->address_line_1 }}</p>
                        @if($addr->address_line_2)
                            <p class="text-zinc-300">{{ $addr->address_line_2 }}</p>
                        @endif
                        <p class="text-zinc-400">{{ $addr->city }}, {{ $addr->state }} {{ $addr->postal_code }}</p>
                        <p class="text-zinc-400">{{ $addr->country }}</p>
                        <p class="font-mono text-zinc-500 text-[11px] pt-1">{{ $addr->phone }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Add Address Card -->
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-6 sm:p-8 space-y-4 max-w-xl">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white">Save Delivery Destination</h3>
                <form action="{{ route('account.address') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">First Name</label>
                            <input type="text" name="first_name" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                        </div>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">Last Name</label>
                            <input type="text" name="last_name" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-zinc-400 mb-1">Street Address</label>
                        <input type="text" name="address_line_1" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">City</label>
                            <input type="text" name="city" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                        </div>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">State</label>
                            <input type="text" name="state" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                        </div>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">Postal Code</label>
                            <input type="text" name="postal_code" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-zinc-400 mb-1">Country</label>
                        <input type="text" name="country" value="United States" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-3 py-2 text-white">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-1">
                        <input type="checkbox" name="is_default" value="1" class="rounded border-zinc-800 bg-zinc-950 text-amber-500 focus:ring-0">
                        <span class="text-zinc-300">Set as my default shipping address</span>
                    </label>
                    <button type="submit"
                            class="rounded-xl bg-amber-500 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                        Save Destination
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. Profile & Security Tab -->
        <div x-show="tab === 'profile'" class="space-y-6 max-w-xl" style="display: none;">
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-6 sm:p-8 space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white">Account Information</h3>
                <form action="{{ route('account.profile') }}" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-zinc-400 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white">
                    </div>

                    <div>
                        <label class="block font-bold text-zinc-400 mb-1">Primary Email (Locked)</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-xl border border-zinc-800 bg-zinc-950/50 px-4 py-2.5 text-zinc-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block font-bold text-zinc-400 mb-1">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white">
                    </div>

                    <div class="pt-4 border-t border-zinc-800 space-y-4">
                        <h4 class="font-bold text-white uppercase">Change Password</h4>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">Current Password</label>
                            <input type="password" name="current_password" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white">
                        </div>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">New Password (8+ characters)</label>
                            <input type="password" name="new_password" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white">
                        </div>
                        <div>
                            <label class="block font-bold text-zinc-400 mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-2.5 text-white">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="rounded-xl bg-amber-500 px-6 py-3 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layout>
