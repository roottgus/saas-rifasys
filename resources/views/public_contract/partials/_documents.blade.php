{{-- resources/views/public_contract/partials/_documents.blade.php --}}

{{-- ===== Sección 1: Información de contacto ===== --}}
<div class="rounded-xl border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Información de Contacto
        </h3>
    </div>

    <div class="p-6 space-y-6">
        {{-- Dirección completa --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Dirección completa <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    name="client_address"
                    required
                    value="{{ old('client_address', $contract->client_address) }}"
                    class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Calle, número, ciudad, provincia">
            </div>
            @error('client_address')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Nombre de la rifa --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Nombre de la rifa <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    name="raffle_name"
                    required
                    value="{{ old('raffle_name', $contract->raffle_name) }}"
                    class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    placeholder="Ej: Gran Rifa Toyota Corolla 2025">
            </div>
            @error('raffle_name')
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

{{-- ===== Sección 2: Documentación (uploads) ===== --}}
<div class="rounded-xl border border-slate-200 overflow-hidden mt-6">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Documentación Requerida
        </h3>
    </div>

    <div class="p-6">
        <div class="grid sm:grid-cols-2 gap-6">
            {{-- Cédula --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Foto de cédula de identidad <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="file"
                        name="cedula_file"
                        accept=".jpg,.jpeg,.png,.pdf"
                        required
                        class="hidden"
                        id="cedula-upload">
                    <label for="cedula-upload" class="block w-full cursor-pointer">
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition duration-200">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-slate-600">
                                <span class="font-semibold text-blue-600">Haga clic para cargar</span> o arrastre el archivo aquí
                            </p>
                            <p class="text-xs text-slate-500 mt-1">JPG, PNG o PDF (máx. 5MB)</p>
                            <div id="cedula-filename" class="mt-3 text-sm text-emerald-600 font-medium hidden"></div>
                        </div>
                    </label>
                </div>
                @error('cedula_file')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- CONALOT (opcional) --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Permiso CONALOT <span class="text-slate-400 font-normal">(opcional)</span>
                </label>
                <div class="relative">
                    <input
                        type="file"
                        name="conalot_permit_file"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        id="conalot-upload">
                    <label for="conalot-upload" class="block w-full cursor-pointer">
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-500 hover:bg-emerald-50 transition duration-200">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M8 14v20a4 4 0 004 4h24a4 4 0 004-4V14M16 10V8a4 4 0 014-4h8a4 4 0 014 4v2m-16 12h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-slate-600">
                                <span class="font-semibold text-emerald-600">Cargar permiso</span>
                            </p>
                            <p class="text-xs text-slate-500 mt-1">JPG, PNG o PDF (máx. 10MB)</p>
                            <div id="conalot-filename" class="mt-3 text-sm text-emerald-600 font-medium hidden"></div>
                        </div>
                    </label>
                </div>
                @error('conalot_permit_file')
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
</div>
