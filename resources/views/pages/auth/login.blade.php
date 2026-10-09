<x-layout title="Client Sign In">
    <div class="py-16 sm:py-24 bg-zinc-950 flex items-center justify-center px-4">
        <div class="w-full max-w-md space-y-8 rounded-3xl border border-zinc-800 bg-zinc-900/60 p-8 sm:p-10 shadow-2xl backdrop-blur-xl">

            <div class="text-center space-y-2">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-zinc-950 font-black text-xl shadow-lg shadow-amber-500/20">
                    Z
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Atelier Client Sign In</h1>
                <p class="text-xs text-zinc-400">Access your curated orders, saved wishlists, and preferences.</p>
            </div>

            <!-- Demo Quick Sign-in Section -->
            <div class="rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 space-y-2.5">
                <span class="block text-[10px] font-bold uppercase tracking-widest text-amber-400">Reviewer Instant Demo Access</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('demo.login', ['role' => 'admin']) }}"
                       class="flex items-center justify-center py-2 px-3 rounded-xl bg-zinc-900/90 border border-amber-500/30 text-amber-300 font-bold hover:bg-amber-500/20 transition text-center text-[11px]">
                        👑 Admin Concierge
                    </a>
                    <a href="{{ route('demo.login', ['role' => 'customer']) }}"
                       class="flex items-center justify-center py-2 px-3 rounded-xl bg-zinc-900/90 border border-amber-500/30 text-amber-300 font-bold hover:bg-amber-500/20 transition text-center text-[11px]">
                        💎 Elena (Customer)
                    </a>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Email Address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', 'customer@zyricz.com') }}"
                           required
                           autofocus
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    @error('email') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="font-bold text-zinc-300 uppercase tracking-wider">Password</label>
                        <span class="text-[11px] text-zinc-500">demo: password</span>
                    </div>
                    <input type="password"
                           name="password"
                           value="password"
                           required
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-zinc-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-zinc-800 bg-zinc-950 text-amber-500 focus:ring-0">
                        <span>Remember credentials</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-amber-400 to-amber-600 py-3.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:from-amber-300 hover:to-amber-500 transition shadow-lg shadow-amber-500/20">
                        Authenticate & Continue
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-zinc-800/80 text-center text-xs text-zinc-500">
                New connoisseur?
                <a href="{{ route('register') }}" class="text-amber-400 hover:underline font-semibold ml-1">
                    Register an Atelier account
                </a>
            </div>
        </div>
    </div>
</x-layout>
