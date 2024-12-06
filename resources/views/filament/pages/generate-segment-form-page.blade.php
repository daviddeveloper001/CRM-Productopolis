<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        
        {{ $this->submitAction }}

        <x-filament-actions::modals/>
    </form>

</x-filament-panels::page>
