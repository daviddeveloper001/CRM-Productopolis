<x-filament-panels::page>
    {{-- <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Segmento</th>
                    <th class="border border-gray-300 px-4 py-2">Nombre cliente</th>
                    <th class="border border-gray-300 px-4 py-2">Primer Nombre</th>
                    <th class="border border-gray-300 px-4 py-2">Teléfono</th>
                    <th class="border border-gray-300 px-4 py-2">Correo</th>
                    <th class="border border-gray-300 px-4 py-2">Ciudad</th>
                    <th class="border border-gray-300 px-4 py-2">Departamento</th>
                    <th class="border border-gray-300 px-4 py-2">Ordenes</th>
                    <th class="border border-gray-300 px-4 py-2">Entregadas</th>
                    <th class="border border-gray-300 px-4 py-2">Devoluciones</th>
                    <th class="border border-gray-300 px-4 py-2">F primera orden</th>
                    <th class="border border-gray-300 px-4 py-2">F última orden</th>
                    <th class="border border-gray-300 px-4 py-2">F última orden entregada</th>
                    <th class="border border-gray-300 px-4 py-2">Ingresos</th>
                    <th class="border border-gray-300 px-4 py-2">Valor devoluciones</th>
                    <th class="border border-gray-300 px-4 py-2">Método de pago</th>
                    <th class="border border-gray-300 px-4 py-2">Vendedor</th>

                    <th class="border border-gray-300 px-4 py-2">Es común</th>
                    <th class="border border-gray-300 px-4 py-2">Tienda</th>
                    <th class="border border-gray-300 px-4 py-2">Último item comprado</th>
                    <th class="border border-gray-300 px-4 py-2">Antepenúltimo item comprado</th>
                    <th class="border border-gray-300 px-4 py-2">Días desde última compra</th>
                    <th class="border border-gray-300 px-4 py-2">Alerta de Devolución</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $register)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->segment->type ?? 'Sin segmento' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->customer_name ?? 'Sin venta' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->first_name ?? 'Sin venta' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->phone ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->email ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->city->name ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->customer->city->department->name ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->orders_number ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->number_entries ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->returns_number ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->last_order_date_delivered ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->last_order_date_delivered ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->last_order_date_delivered ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->total_revenues ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2"> 
                            {{ $register->sale->return_value ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->paymentMethod->name ?? 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->seller->name ?? 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->customer->is_frequent_customer ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->shop->name ?? 'N/A' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->last_item_purchased ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->last_item_purchased ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->last_days_purchase_days ?? 'Sin cliente' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $register->sale->returnAlert->type ?? 'Sin cliente' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center border border-gray-300 px-4 py-2">
                            No hay registros disponibles.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div> --}}
    {{ $this->table }}
</x-filament-panels::page>

