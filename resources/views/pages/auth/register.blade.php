<x-layout title="Register Atelier Account">
    <div class="py-16 sm:py-24 bg-zinc-950 flex items-center justify-center px-4">
        <div class="w-full max-w-md space-y-8 rounded-3xl border border-zinc-800 bg-zinc-900/60 p-8 sm:p-10 shadow-2xl backdrop-blur-xl">

            <div class="text-center space-y-2">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-zinc-950 font-black text-xl shadow-lg shadow-amber-500/20">
                    Z
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Create Atelier Account</h1>
                <p class="text-xs text-zinc-400">Join our private registry for curated allocations and tracking.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Full Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           placeholder="e.g. Julian Croft"
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    @error('name') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Email Address</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="julian@example.com"
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    @error('email') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Password (Minimum 8 Characters)</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                    @error('password') <p class="text-rose-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-zinc-300 mb-1.5 uppercase tracking-wider">Confirm Password</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full rounded-xl border border-zinc-800 bg-zinc-950 px-4 py-3 text-white placeholder-zinc-600 focus:border-amber-500 focus:outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-amber-400 to-amber-600 py-3.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:from-amber-300 hover:to-amber-500 transition shadow-lg shadow-amber-500/20">
                        Create Account & Join
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-zinc-800/80 text-center text-xs text-zinc-500">
                Already registered?
                <a href="{{ route('login') }}" class="text-amber-400 hover:underline font-semibold ml-1">
                    Sign In
                </a>
            </div>
        </div>
    </div>
</x-layout>
