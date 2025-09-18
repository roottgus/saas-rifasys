{{-- $contract disponible por parámetro --}}
<div class="bg-gradient-to-r from-slate-50 to-blue-50 px-8 py-6 border-b border-slate-100">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Número de contrato</span>
            </div>
            <div class="text-2xl font-bold text-slate-800">{{ $contract->contract_number }}</div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Contrato Digital Seguro
            </span>
        </div>
    </div>

    {{-- Tarjetas de cliente --}}
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Cliente --}}
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-slate-500 font-medium">Cliente</p>
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $contract->client_name }}</p>
                    <p class="text-xs text-slate-500">C.I.: {{ $contract->client_id_number }}</p>
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-slate-500 font-medium">Correo electrónico</p>
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $contract->client_email }}</p>
                </div>
            </div>
        </div>

        {{-- Teléfono --}}
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-slate-500 font-medium">Teléfono</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $contract->client_phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
