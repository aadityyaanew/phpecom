<div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none max-w-sm w-full px-4"
     x-data>
    <template x-for="item in $store.toast.items" :key="item.id">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="pointer-events-auto flex items-center justify-between gap-3 rounded-xl border p-4 shadow-2xl backdrop-blur-xl"
             :class="{
                 'border-emerald-500/30 bg-zinc-900/95 text-emerald-300': item.type === 'success',
                 'border-rose-500/30 bg-zinc-900/95 text-rose-300': item.type === 'error',
                 'border-amber-500/30 bg-zinc-900/95 text-amber-300': item.type === 'info'
             }">
            <div class="flex items-center gap-2.5">
                <template x-if="item.type === 'success'">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span>
                </template>
                <template x-if="item.type === 'error'">
                    <span class="flex h-2 w-2 rounded-full bg-rose-400"></span>
                </template>
                <template x-if="item.type === 'info'">
                    <span class="flex h-2 w-2 rounded-full bg-amber-400"></span>
                </template>
                <span class="text-xs font-semibold" x-text="item.message"></span>
            </div>
            <button type="button"
                    @click="$store.toast.remove(item.id)"
                    class="text-zinc-400 hover:text-white transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
