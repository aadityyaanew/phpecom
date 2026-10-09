<x-layout title="Your Saved Wishlist">
    <div class="bg-zinc-950 border-b border-zinc-900 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-black text-white tracking-tight">Your Saved Wishlist</h1>
            <p class="text-xs text-zinc-400 mt-1">Curated objects and timepieces you have earmarked for future allocation.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($products->isEmpty())
            <div class="rounded-3xl border border-zinc-800 bg-zinc-900/40 p-16 text-center space-y-4 max-w-xl mx-auto">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-zinc-800/80 text-zinc-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-xl font-bold text-white">No objects saved yet</h2>
                    <p class="text-xs text-zinc-400">Click the heart icon on any creation in our catalog to save it here.</p>
                </div>
                <a href="{{ route('products.index') }}"
                   class="inline-flex rounded-xl bg-amber-500 px-8 py-3.5 text-xs font-bold uppercase tracking-wider text-zinc-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                    Explore Curated Collections
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="flex flex-col justify-between">
                        <x-product-card :product="$product" />
                        <form action="{{ route('wishlist.move') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 rounded-xl border border-zinc-800 bg-zinc-900 py-2.5 text-xs font-bold uppercase tracking-wider text-amber-400 hover:border-amber-500/50 hover:bg-amber-500/10 transition">
                                <span>Move Directly to Bag</span>
                                &rarr;
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
