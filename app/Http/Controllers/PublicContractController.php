<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContractSignedMail;
use Carbon\Carbon;

class PublicContractController extends Controller
{
    /**
     * Muestra el contrato al cliente para revisión y firma.
     */
    public function show($uuid)
    {
        $contract = Contract::where('uuid', $uuid)->firstOrFail();
        return view('public_contract.show', compact('contract'));
    }

    /**
     * Procesa la aceptación/firma del contrato (con consentimiento legal), genera el PDF y envía el correo.
     */
    public function accept(Request $request, $uuid)
{
    $contract = Contract::where('uuid', $uuid)->firstOrFail();

    // Validar campos y archivos (incluida la firma)
    $request->validate([
        'client_address'       => 'required|string|max:255',
        'raffle_name'          => 'required|string|max:255',
        'cedula_file'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'conalot_permit_file'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        'accept_disclaimer'    => 'required',
        'signature_image'      => 'required|string', // base64 PNG del canvas
    ], [
        'client_address.required' => 'Debes ingresar tu dirección.',
        'raffle_name.required'    => 'Debes indicar el nombre de la rifa.',
        'cedula_file.required'    => 'Debes subir tu cédula para poder firmar el contrato.',
        'accept_disclaimer.required' => 'Debes aceptar la declaración de responsabilidad para continuar.',
        'signature_image.required' => 'Debes firmar electrónicamente el contrato.',
    ]);

    DB::transaction(function () use ($request, $contract) {
        // Guarda los campos del cliente
        $contract->client_address = $request->input('client_address');
        $contract->raffle_name    = $request->input('raffle_name');

        // Sube archivos
        if ($request->hasFile('cedula_file')) {
            $contract->cedula_file = $request->file('cedula_file')->store('contracts/cedulas', 'public');
        }
        if ($request->hasFile('conalot_permit_file')) {
            $contract->conalot_permit_file = $request->file('conalot_permit_file')->store('contracts/permisos', 'public');
        }

        // Consentimiento legal
        $contract->disclaimer_accepted_text = $request->input('accept_disclaimer_text');
        $contract->disclaimer_accepted_ip   = $request->ip();
        $contract->disclaimer_accepted_at   = Carbon::now();

        // Procesar y guardar la firma manuscrita (imagen PNG en storage)
        $signatureData = $request->input('signature_image'); // base64 data:image/png
        if (preg_match('/^data:image\/png;base64,/', $signatureData)) {
            $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
            $signatureData = base64_decode($signatureData);
            $fileName = 'contracts/signatures/contract-signature-' . $contract->uuid . '-' . now()->timestamp . '.png';
            Storage::disk('public')->put($fileName, $signatureData);
            $contract->signature_image_path = $fileName;
            $contract->signature_signed_at = Carbon::now();
            $contract->signature_ip = $request->ip();
            $contract->signature_name = $contract->client_name; // o puedes pedir el nombre del firmante
        }

        // Cambiar status y guardar
        $contract->status     = 'signed';
        $contract->signed_at  = Carbon::now();
        $contract->save();

        // Genera el PDF profesional con la firma incluida
        $pdf = Pdf::loadView('contracts.pdf', [
            'contract' => $contract,
            'firma_cliente_url' => $contract->signature_image_path ? asset('storage/' . $contract->signature_image_path) : null,
        ])->setPaper('a4');
        $filename = "Contrato_RS-CON-{$contract->contract_number}.pdf";
        $path = "contracts/pdfs/{$filename}";

        Storage::disk('public')->put($path, $pdf->output());
        $contract->file_path = $path;
        $contract->save();
    });

    // Enviar email al cliente y admin con PDF adjunto
    try {
        Mail::to($contract->client_email)
            ->cc('info@publienred.com')
            ->send(new ContractSignedMail($contract));
    } catch (\Throwable $e) {
        // Log error si el envío falla
    }

    // Redirige con mensaje de éxito
    return redirect()->route('contrato.firma.show', $contract->uuid)
        ->with('success', '¡Contrato firmado exitosamente! Revisa tu correo para descargar el PDF.');
}


}
