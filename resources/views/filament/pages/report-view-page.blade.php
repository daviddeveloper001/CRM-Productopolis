<x-filament-panels::page>

    {{-- <h2 class="text-xl font-bold mb-4">Reporte para </h2>
        <p>Información adicional sobre el registro</p>

        {{ $this->table }} --}}

    <x-filament::card>
        <h2 class="text-xl font-bold">Detalles de la Campaña</h2>
        <p><strong>ID:</strong> {{ $this->campaign->id }}</p>
        <p><strong>Nombre:</strong> {{ $this->campaign->name }}</p>
    </x-filament::card>

    @foreach ($blocks as $block)
        <x-filament::card>
            <h2 class="text-xl font-bold">Bloques de la Campaña</h2>
            <h3 class="text-xl font-bold">Nombre del Bloque: <span>{{ $block->name }}</span> </h3>
            
            {{ $this->table }}
        </x-filament::card>
    @endforeach



    {{-- @foreach ($blocks as $block)
        <x-filament::card>
            <h2 class="text-xl font-bold">Bloque: {{ $block->name }}</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID</th>
                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre del Cliente</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ventas Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ventas Después</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ingresos Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ingresos Después</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor Devoluciones Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor Devoluciones Después</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Órdenes Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Órdenes Después</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Entregadas Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Entregadas Después</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Devoluciones Antes</th>

                        <th scope="col"
                            class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Devoluciones Después</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($block->segment->customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->first_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::card>
    @endforeach --}}


</x-filament-panels::page>
