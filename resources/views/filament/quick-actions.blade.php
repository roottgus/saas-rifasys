<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Acciones Rápidas
        </x-slot>

        <x-slot name="headerEnd">
            <x-filament::badge color="success" size="sm">
                {{ now()->format('H:i') }}
            </x-filament::badge>
        </x-slot>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach($actions as $action)
                <a 
                    href="{{ $action['url'] }}"
                    class="group relative flex flex-col items-center justify-center rounded-lg border border-gray-200 bg-white p-6 transition-all hover:border-{{ $action['color'] }}-500 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800"
                >
                    <div class="mb-3 rounded-full bg-{{ $action['color'] }}-50 p-3 transition-transform group-hover:scale-110 dark:bg-{{ $action['color'] }}-500/10">
                        <x-dynamic-component 
                            :component="'heroicon-o-' . str_replace('heroicon-o-', '', $action['icon'])"
                            class="h-6 w-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400"
                        />
                    </div>
                    
                    <span class="text-center text-sm font-medium text-gray-900 dark:text-white">
                        {{ $action['label'] }}
                    </span>
                    
                    <div class="absolute inset-x-0 bottom-0 h-1 scale-x-0 transform rounded-b-lg bg-{{ $action['color'] }}-500 transition-transform group-hover:scale-x-100"></div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>