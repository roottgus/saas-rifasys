<div class="mb-4 px-4 py-3 rounded-xl bg-yellow-50 border-l-4 border-yellow-400 text-yellow-900 shadow font-semibold flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    Has alcanzado el <b>límite de rifas</b> de tu plan (<span class="capitalize">{{ $plan }}</span>): <b>{{ $rifasLimit }}</b> rifa(s).
    <span class="ml-2 text-sm text-gray-500">Has creado <b>{{ $rifasCount }}</b> de <b>{{ $rifasLimit }}</b> permitidas.</span>
    <a href="https://wa.me/584220076738" class="ml-auto underline text-yellow-700 hover:text-yellow-900 font-medium" target="_blank">
        ¡Solicita tu upgrade!
    </a>
</div>
