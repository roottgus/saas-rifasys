{{-- resources/views/store/partials/rifa-stats.blade.php --}}

<div class="flex gap-3 md:gap-4 justify-center mt-8 px-2 overflow-x-auto scrollbar-hide">
    {{-- Vendidos --}}
    <div class="relative flex flex-col justify-center items-start min-w-[120px] max-w-[160px] rounded-2xl bg-blue-50 border border-blue-100 shadow-sm px-4 py-3 overflow-hidden">
        <span class="text-[11px] font-semibold text-blue-700/80">Vendidos</span>
        <span class="text-2xl font-extrabold text-blue-900 mt-1">{{ number_format($paid) }}</span>
        {{-- Watermark --}}
        <span class="absolute right-2 bottom-2 text-blue-200/60 text-4xl pointer-events-none select-none" aria-hidden="true">
            <i class="fa-solid fa-ticket"></i>
        </span>
    </div>

    {{-- Reservados --}}
    <div class="relative flex flex-col justify-center items-start min-w-[120px] max-w-[160px] rounded-2xl bg-amber-50 border border-amber-100 shadow-sm px-4 py-3 overflow-hidden">
        <span class="text-[11px] font-semibold text-amber-700/80">Reservados</span>
        <span class="text-2xl font-extrabold text-amber-900 mt-1">{{ number_format($reserved) }}</span>
        {{-- Watermark --}}
        <span class="absolute right-2 bottom-2 text-amber-200/70 text-4xl pointer-events-none select-none" aria-hidden="true">
            <i class="fa-solid fa-hourglass-half"></i>
        </span>
    </div>

    {{-- Disponibles --}}
    <div class="relative flex flex-col justify-center items-start min-w-[120px] max-w-[160px] rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm px-4 py-3 overflow-hidden">
        <span class="text-[11px] font-semibold text-emerald-700/80">Disponibles</span>
        <span class="text-2xl font-extrabold text-emerald-900 mt-1">{{ number_format($available) }}</span>
        {{-- Watermark --}}
        <span class="absolute right-2 bottom-2 text-emerald-200/70 text-4xl pointer-events-none select-none" aria-hidden="true">
            <i class="fa-solid fa-circle-check"></i>
        </span>
    </div>
</div>
