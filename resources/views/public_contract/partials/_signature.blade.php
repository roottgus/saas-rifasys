{{-- resources/views/public_contract/partials/_signature.blade.php --}}

<div class="rounded-xl border border-slate-200 overflow-hidden mt-6">
    <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-6 py-4 border-b border-emerald-200">
        <h3 class="text-lg font-semibold text-slate-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Firma Electrónica del Cliente
        </h3>
    </div>

    <div class="p-6">
        <div class="rounded-xl bg-white border-2 border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-b from-slate-50 to-white p-4">
                {{-- Canvas de la firma --}}
                <canvas
                    id="signature-pad"
                    width="800"
                    height="250"
                    class="firma-canvas w-full bg-white rounded-lg"
                    style="touch-action: none;">
                </canvas>

                {{-- Botones y texto de ayuda --}}
                <div class="mt-4 flex items-center justify-between">
                    <button
                        type="button"
                        id="clear-signature"
                        class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-semibold text-slate-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Limpiar firma
                    </button>

                    <span class="text-sm text-slate-500 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Firme con su dedo o mouse
                    </span>
                </div>
            </div>
        </div>

        {{-- Inputs ocultos para guardar la firma --}}
        <input type="hidden" name="signature_data" id="signature_data">
        <input type="hidden" name="signature_image" id="signature_image">

        @error('signature_data')
            <p class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
