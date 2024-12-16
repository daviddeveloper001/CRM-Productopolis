<x-filament-panels::page>
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4">Reporte para {{-- {{ $record->name }} --}}</h2>
        <p>Información adicional sobre el registro</p>

        {{ $this->table }}
    </x-filament::card>
</x-filament-panels::page>
