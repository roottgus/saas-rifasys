<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;
use App\Models\Tenant;

class ClientContractController extends Controller
{
    public function show(Request $request)
    {
        // Encuentra el contrato firmado más reciente del usuario y su tenant
        $user = Auth::user();
        $tenant = Tenant::where('notify_email', $user->email)->first();
        if (!$tenant) {
            abort(403, 'No tienes un contrato activo.');
        }
        $contract = Contract::where('tenant_id', $tenant->id)
            ->where('status', 'signed')
            ->latest('signed_at')
            ->first();

        return view('client_contract.show', compact('contract', 'tenant'));
    }
}
