@extends('layouts.contract')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-emerald-50/20">
    {{-- HEADER PROFESIONAL CON LOGOS --}}
    @include('public_contract.partials._header')

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            {{-- Encabezado de contrato + tarjetas cliente --}}
            @include('public_contract.partials._contract_header', ['contract' => $contract])

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(($contract->status ?? null) !== 'signed')
                    {{-- Indicador de progreso (solo cuando NO está firmado) --}}
                    @include('public_contract.partials._progress', [
                        'currentStep' => 2,
                        'steps' => ['Datos verificados', 'Completar información', 'Firma completada'],
                    ])

                    {{-- Resumen de términos --}}
                    @include('public_contract.partials._terms_summary')

                    {{-- FORMULARIO DE FIRMA --}}
                    <form id="firma-form" method="POST" action="{{ route('contrato.firma.aceptar', $contract->uuid) }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Sección: Información de contacto + Documentación --}}
                        @include('public_contract.partials._documents', ['contract' => $contract])

                        {{-- Descargo legal --}}
                        @include('public_contract.partials._legal_disclaimer')

                        {{-- Firma digital --}}
                        @include('public_contract.partials._signature')

                        {{-- Botón enviar --}}
                        @include('public_contract.partials._submit')
                    </form>
                @else
                    {{-- Mensaje “ya firmado” --}}
                    @include('public_contract.partials._signed_message', ['contract' => $contract])
                @endif
            </div>

            {{-- Footer de la tarjeta --}}
            @include('public_contract.partials._footer')
        </div>

        {{-- Insignias de confianza (opcional: puedes moverlo a partial si quieres) --}}
        {{-- Si lo quieres en partial, crea _trust_badges.blade.php y reemplaza la línea de abajo por: @include('public_contract.partials._trust_badges') --}}
        {{-- (Se mantiene fuera para no extender demasiado este archivo) --}}
    </div>
</div>
@endsection

@push('scripts')
    @include('public_contract.partials._scripts')
@endpush
