{{-- Grid de números embebido, visual limpio y responsive --}}
@php
    $totalCount = $nums instanceof \Illuminate\Support\Collection ? $nums->count() : count($nums);
    $pageSize = 100; // Más fluido en mobile
    $pages = (int) ceil($totalCount / $pageSize);
@endphp

<div 
    x-data="{
        page: 1,
        pageSize: {{ $pageSize }},
        total: {{ $totalCount }},
        get start() { return (this.page - 1) * this.pageSize; },
        get end() { return Math.min(this.page * this.pageSize, this.total); },
        goTo(p) { 
            this.page = p; 
            this.$nextTick(() => { 
                let grid = this.$root.querySelector('.numbers-grid');
                if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        }
    }"
    class="w-full mt-5"
>
    {{-- Grid responsive y aireado --}}
    <div class="numbers-grid grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 gap-2 md:gap-2.5 bg-white rounded-3xl p-4 border border-blue-100 shadow-lg transition-all duration-200">
        @foreach($nums as $i => $n)
            @php
                $raw   = $n->estado;
                $state = $raw instanceof \BackedEnum ? $raw->value : (string) $raw;
                $num   = (int) $n->numero;
                $label = $totalCount >= 10000 ? str_pad((string)$num, 4, '0', STR_PAD_LEFT)
                        : ($totalCount >= 1000 ? str_pad((string)$num, 3, '0', STR_PAD_LEFT)
                        : ($totalCount >= 100 ? str_pad((string)$num, 2, '0', STR_PAD_LEFT) : (string)$num));
                $isFree = $state === 'disponible';
            @endphp
           <button
                x-show="{{ $i }} >= ((page-1)*pageSize) && {{ $i }} < (page*pageSize)"
                type="button"
                class="embed-num num flex items-center justify-center text-base md:text-lg font-bold rounded-xl border select-none transition-all duration-200 h-12 w-14 md:h-14 md:w-16
                    @if($state==='disponible') 
                        bg-white border-blue-100 text-gray-900 
                        hover:bg-[var(--primary)] hover:text-white hover:border-[var(--primary)] 
                        cursor-pointer shadow-md
                    @endif
                    @if($state==='reservado') 
                        bg-yellow-100 border-yellow-200 text-yellow-700 opacity-80 cursor-not-allowed shadow-none 
                    @endif
                    @if($state==='pagado')    
                        bg-gray-100 border-gray-200 text-gray-400 opacity-60 cursor-not-allowed shadow-none 
                    @endif"
                :class="manualSelectedNums && manualSelectedNums.includes('{{ $label }}') 
                    ? 'bg-[var(--primary)] text-white border-[var(--primary)] ring-2 ring-[var(--primary)] scale-105 shadow-lg' 
                    : ''"
                :disabled="!{{ $isFree ? 'true' : 'false' }} || (manualSelectedNums && manualSelectedNums.length >= max && !manualSelectedNums.includes('{{ $label }}'))"
                @click.prevent="toggleManualNum('{{ $label }}')"
                data-label="{{ $label }}"
                data-num="{{ $num }}"
                aria-pressed="false"
                aria-label="Número {{ $label }} {{ $state }}"
            >{{ $label }}</button>
        @endforeach
    </div>

    {{-- Paginador sticky --}}
    <div class="pager flex items-center justify-center gap-2 mt-3 mb-1 sticky bottom-0 bg-white/95 py-2 z-10 rounded-xl shadow-sm">
        <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800 font-bold" @click="goTo(1)" :disabled="page === 1">«</button>
        <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800 font-bold" @click="goTo(page-1)" :disabled="page === 1">‹</button>
        <div class="min-w-[80px] px-2 py-1 rounded-full bg-blue-700 text-white font-bold text-xs pager-info select-none">
            Pag: <span x-text="page"></span>/{{ $pages }}
        </div>
        <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800 font-bold" @click="goTo(page+1)" :disabled="page === {{ $pages }}">›</button>
        <button class="w-8 h-8 rounded-full border bg-white text-xl text-blue-800 font-bold" @click="goTo({{ $pages }})" :disabled="page === {{ $pages }}">»</button>
    </div>
</div>
