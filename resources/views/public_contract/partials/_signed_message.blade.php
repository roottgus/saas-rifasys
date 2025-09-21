<div class="rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-8">
    <div class="flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-emerald-800 mb-2">Contrato Firmado Exitosamente</h3>
        <p class="text-emerald-700">
            Este contrato fue firmado digitalmente el 
            <strong>{{ $contract->signed_at->format('d/m/Y') }}</strong> 
            a las <strong>{{ $contract->signed_at->format('H:i') }}</strong>
        </p>
        <div class="mt-6 flex space-x-3">
            
        </div>
    </div>
</div>
