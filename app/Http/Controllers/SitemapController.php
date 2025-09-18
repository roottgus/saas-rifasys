<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Página principal (landing)
        $sitemap .= $this->createUrl('/', 1.0, 'weekly');
        
        // Páginas informativas que ahora SÍ existen
        $sitemap .= $this->createUrl('/contacto', 0.8, 'monthly');
        $sitemap .= $this->createUrl('/terminos', 0.3, 'yearly');
        $sitemap .= $this->createUrl('/privacidad', 0.3, 'yearly');
        
        // Páginas de autenticación (cuando las agregues)
        if (Route::has('login')) {
            $sitemap .= $this->createUrl('/login', 0.5, 'yearly');
        }
        
        if (Route::has('register')) {
            $sitemap .= $this->createUrl('/registro', 0.6, 'monthly');
        }
        
        // Si quieres indexar las rifas públicas de los tenants (OPCIONAL)
        // IMPORTANTE: Solo activa esto si quieres que Google indexe las rifas de tus clientes
        if ($this->shouldIndexTenantRaffles()) {
            $sitemap .= $this->addTenantRaffles();
        }
        
        $sitemap .= '</urlset>';
        
        return Response::make($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600' // Cache por 1 hora
        ]);
    }
    
    /**
     * Crear un elemento URL para el sitemap
     */
    private function createUrl($path, $priority = 0.5, $changefreq = 'monthly', $lastmod = null)
    {
        $url = '<url>';
        $url .= '<loc>' . URL::to($path) . '</loc>';
        $url .= '<lastmod>' . ($lastmod ?: now()->toIso8601String()) . '</lastmod>';
        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . number_format($priority, 1) . '</priority>';
        $url .= '</url>';
        
        return $url;
    }
    
    /**
     * Determinar si se deben indexar las rifas de los tenants
     * Puedes cambiar esta lógica según tus necesidades
     */
    private function shouldIndexTenantRaffles()
    {
        // Por defecto NO indexar rifas de tenants
        // Cambia a true si quieres que Google indexe las rifas de tus clientes
        return config('app.index_tenant_raffles', false);
    }
    
    /**
     * Agregar rifas de tenants al sitemap
     */
    private function addTenantRaffles()
    {
        $tenantUrls = '';
        
        try {
            // Verificar si el modelo Tenant existe
            if (!class_exists('\App\Models\Tenant')) {
                return $tenantUrls;
            }
            
            $tenants = \App\Models\Tenant::where('active', true)
                ->where('public', true) // Solo tenants que quieren ser públicos
                ->take(100) // Limitar para evitar sitemaps muy grandes
                ->get();
            
            foreach ($tenants as $tenant) {
                // Página principal del tenant
                $tenantUrls .= $this->createUrl(
                    '/t/' . $tenant->slug, 
                    0.7, 
                    'daily',
                    $tenant->updated_at->toIso8601String()
                );
                
                // Rifas activas del tenant (máximo 50 por tenant)
                $rifas = $tenant->rifas()
                    ->where('activa', true)
                    ->where('fecha_sorteo', '>', now()) // Solo rifas futuras
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get();
                
                foreach ($rifas as $rifa) {
                    $tenantUrls .= $this->createUrl(
                        '/t/' . $tenant->slug . '/r/' . $rifa->slug, 
                        0.6, 
                        'weekly',
                        $rifa->updated_at->toIso8601String()
                    );
                }
            }
        } catch (\Exception $e) {
            // Si hay algún error, simplemente no incluir las rifas de tenants
            \Log::error('Error generando sitemap de tenants: ' . $e->getMessage());
        }
        
        return $tenantUrls;
    }
    
    /**
     * Generar un sitemap índice si tienes múltiples sitemaps
     * Útil cuando tienes muchas URLs (>50,000)
     */
    public function sitemapIndex()
    {
        $sitemapIndex = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemapIndex .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Sitemap principal
        $sitemapIndex .= '<sitemap>';
        $sitemapIndex .= '<loc>' . URL::to('/sitemap-main.xml') . '</loc>';
        $sitemapIndex .= '<lastmod>' . now()->toIso8601String() . '</lastmod>';
        $sitemapIndex .= '</sitemap>';
        
        // Sitemap de tenants (si aplica)
        if ($this->shouldIndexTenantRaffles()) {
            $sitemapIndex .= '<sitemap>';
            $sitemapIndex .= '<loc>' . URL::to('/sitemap-tenants.xml') . '</loc>';
            $sitemapIndex .= '<lastmod>' . now()->toIso8601String() . '</lastmod>';
            $sitemapIndex .= '</sitemap>';
        }
        
        $sitemapIndex .= '</sitemapindex>';
        
        return Response::make($sitemapIndex, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
}