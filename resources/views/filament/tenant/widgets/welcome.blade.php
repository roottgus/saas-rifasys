<x-filament-widgets::widget>
    <div class="welcome-banner relative overflow-hidden" style="background: rgb(var(--primary-600)); padding: 1.5rem;">
        <div class="relative z-10">
            <!-- Botones flotantes en la esquina superior derecha -->
            <div style="position: absolute; top: 10px; right: 20px; z-index: 20;" class="hidden lg:flex gap-3">
                @if($tenantSlug)
                    <a href="{{ url('/panel/' . $tenantSlug . '/raffles/create') }}"
                        style="display: inline-flex !important; background: rgba(255, 255, 255, 0.9) !important; color: rgb(37, 99, 235) !important; padding: 8px 20px !important; border-radius: 8px !important; font-weight: 600 !important; text-decoration: none !important; align-items: center !important; gap: 8px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;"
                        onmouseover="this.style.background='rgba(255, 255, 255, 1)'"
                        onmouseout="this.style.background='rgba(255, 255, 255, 0.9)'">
                        <x-heroicon-o-plus-circle class="w-5 h-5" />
                        <span>Nueva Rifa</span>
                    </a>
                    <a href="{{ url('/panel/' . $tenantSlug . '/reports') }}"
                        style="display: inline-flex !important; background: rgba(255, 255, 255, 0.15) !important; color: white !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; padding: 8px 20px !important; border-radius: 8px !important; font-weight: 600 !important; text-decoration: none !important; align-items: center !important; gap: 8px !important;"
                        onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'"
                        onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'">
                        <x-heroicon-o-chart-bar class="w-5 h-5" />
                        <span>Ver Reportes</span>
                    </a>
                @else
                    <button disabled 
                        style="background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.5);"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold cursor-not-allowed">
                        <x-heroicon-o-plus-circle class="w-5 h-5" />
                        <span>Nueva Rifa</span>
                    </button>
                    <button disabled 
                        style="background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.5);"
                        class="flex items-center gap-2 px-5 py-2 rounded-lg font-semibold cursor-not-allowed">
                        <x-heroicon-o-chart-bar class="w-5 h-5" />
                        <span>Ver Reportes</span>
                    </button>
                @endif
            </div>

            <!-- Contenido principal -->
            <div class="flex items-center w-full">
                <!-- Columna izquierda: saludo, bienvenida, info -->
                <div class="flex-1 min-w-0 pr-32 lg:pr-80">
                    <h2 class="text-3xl font-bold mb-2 text-white">
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
            </div>

            <!-- Tip del día dinámico con Alpine.js -->
            <div
                id="tip-del-dia"
                x-data="{
                    tips: @js($tips),
                    index: 0,
                    get currentTip() { return this.tips[this.index] },
                    rotate() { this.index = (this.index + 1) % this.tips.length }
                }"
                x-init="setInterval(() => rotate(), 30000)"
                class="mt-6 p-3 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20"
            >
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="#fde047" viewBox="0 0 24 24">
                        <ellipse cx="12" cy="10" rx="7" ry="7" fill="#fde047"/>
                        <rect x="10" y="17" width="4" height="4" rx="1" fill="#fde047" stroke="#facc15" stroke-width="1"/>
                        <rect x="9" y="15.5" width="6" height="2" rx="1" fill="#facc15"/>
                        <path d="M9 18h6" stroke="#facc15" stroke-width="1.4" stroke-linecap="round"/>
                    </svg>
                    <div class="text-sm text-white/90">
                        <strong>Tip del día:</strong>
                        <span
                            x-text="currentTip"
                            x-transition:enter="transition-opacity duration-500"
                            x-transition:leave="transition-opacity duration-500"
                            class="transition-opacity duration-500 ease-in-out opacity-100"
                        >{{ $tips[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>