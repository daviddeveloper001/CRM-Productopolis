<x-filament-panels::page>
    <form wire:submit="submit">
        <div class="mb-4">
            {{ $this->form }}
        </div>

        {{ $this->submitAction }}

        <x-filament-actions::modals />
    </form>


</x-filament-panels::page>
