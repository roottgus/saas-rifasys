{{-- resources/views/public_contract/partials/_legal_disclaimer.blade.php --}}

<div class="rounded-xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-6">
    <div class="flex items-start space-x-3">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <div class="flex-1">
            <h4 class="text-base font-bold text-amber-800 mb-2">Descargo de Responsabilidad Legal</h4>
            <p class="text-sm text-amber-700 mb-4">
                Si no cuenta con el permiso CONALOT, debe aceptar el siguiente descargo de responsabilidad:
            </p>

            <input type="hidden" name="accept_disclaimer_text"
                   value="El cliente asume total responsabilidad por la falta de permiso CONALOT. Exime a Publicidad en Red C.A. de toda responsabilidad legal.">

            <div class="bg-white/70 rounded-lg p-4 border border-amber-200">
                <label for="accept_disclaimer" class="flex items-start cursor-pointer">
                    <input
                        id="accept_disclaimer"
                        type="checkbox"
                        name="accept_disclaimer"
                        required
                        class="mt-1 h-4 w-4 text-amber-600 focus:ring-amber-500 border-amber-300 rounded">

                    <span class="ml-3 text-sm text-slate-700">
                        <strong class="font-semibold">Acepto y entiendo</strong> que asumo total responsabilidad legal por operar sin permiso CONALOT.
                        Eximo completamente a <strong>Publicidad en Red C.A.</strong> de cualquier consecuencia, sanción o responsabilidad legal derivada de esta situación.
                    </span>
                </label>
            </div>

            @error('accept_disclaimer')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
