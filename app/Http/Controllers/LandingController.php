<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Puedes pasar datos a la vista si es necesario
        $data = [
            'meta_title' => 'Rifasys - Tu Negocio de Rifas Online | Plataforma #1',
            'meta_description' => 'Vende, gestiona y crece con la plataforma más completa para rifas digitales. Automatiza todo el proceso y maximiza tus ganancias.',
            'meta_keywords' => 'rifas online, sorteos digitales, plataforma rifas, gestión rifas, vender rifas'
        ];
        
        return view('landing', $data);
    }
    
    /**
     * Handle contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:1000',
        ]);
        
        // Aquí puedes procesar el formulario de contacto
        // Por ejemplo, enviar un email o guardar en base de datos
        
        return redirect()->back()->with('success', '¡Mensaje enviado correctamente!');
    }
    
    /**
     * Handle newsletter subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ]);
        
        // Guardar en la base de datos o servicio de email marketing
        
        return response()->json([
            'success' => true,
            'message' => '¡Te has suscrito exitosamente!'
        ]);
    }
}