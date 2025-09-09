
<x-filament-widgets::widget>
    <div class="welcome-banner relative overflow-hidden">
        {{-- Patrón de fondo decorativo --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="absolute right-0 top-0 -mt-16 -mr-16" width="400" height="400" fill="none" viewBox="0 0 400 400">
                <defs>
                    <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1" fill="currentColor" />
                    </pattern>
                </defs>
                <rect width="400" height="400" fill="url(#dots)" />
            </svg>
        </div>
        
        {{-- Contenido principal --}}
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">
                        <span class="greeting-message">{{ $greeting }}</span>, {{ $userName }}! 👋
                    </h2>
                    <p class="text-white/90 text-lg mb-4">
                        Bienvenido al panel de control de <strong>{{ $tenantName }}</strong>
                    </p>
                    
                    <div class="flex flex-wrap gap-6 text-sm text-white/80">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="w-4 h-4" />
                            <span>Último acceso: {{ $lastLogin }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-sparkles class="w-4 h-4" />
                            <span>Plan: <strong class="text-white">{{ $plan }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-calendar-days class="w-4 h-4" />
                            <span>{{ $daysActive }} días activo</span>
                        </div>
                    </div>
                </div>
                
                {{-- Acciones rápidas --}}
                <div class="hidden lg:flex gap-3">
                    <a href="{{ url('/panel/' . auth()->user()->currentTenant->slug . '/raffles/create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <x-heroicon-o-plus-circle class="w-5 h-5" />
                        <span>Nueva Rifa</span>
                    </a>
                    <a href="{{ url('/panel/' . auth()->user()->currentTenant->slug . '/reports') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 backdrop-blur-sm">
                        <x-heroicon-o-chart-bar class="w-5 h-5" />
                        <span>Ver Reportes</span>
                    </a>
                </div>
            </div>
            
            {{-- Tips o notificaciones importantes --}}
            <div class="mt-6 p-3 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-light-bulb class="w-5 h-5 text-yellow-300 flex-shrink-0 mt-0.5" />
                    <div class="text-sm text-white/90">
                        <strong>Tip del día:</strong> 
                        Optimiza tus rifas compartiendo el enlace en redes sociales para alcanzar más participantes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>