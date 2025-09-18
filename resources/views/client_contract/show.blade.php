@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded shadow p-8 mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center">Mi Contrato de Servicio Rifasys</h2>
    @if($contract)
        <p class="mb-2"><strong>N° Contrato:</strong> {{ $contract->contract_number }}</p>
        <p class="mb-2"><strong>Rifa:</strong> {{ $contract->raffle_name }}</p>
        <p class="mb-2"><strong>Estado:</strong>
            <span class="font-semibold text-green-600">
                {{ $contract->status === 'signed' ? 'Firmado' : ucfirst($contract->status) }}
            </span>
        </p>
        <p class="mb-2"><strong>Fecha de firma:</strong> {{ $contract->signed_at->format('d/m/Y H:i') }}</p>
        <a href="{{ asset('storage/' . $contract->file_path) }}" class="text-blue-600 underline mt-4 block" target="_blank">
            Descargar contrato PDF firmado
        </a>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded">
            Aún no tienes un contrato firmado activo para visualizar.
        </div>
    @endif
</div>
@endsection
