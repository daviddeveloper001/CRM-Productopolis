<div>
    <h2 class="text-lg font-bold">Clientes por Método de Pago</h2>
    @if ($customersByPaymentMethod->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($customersByPaymentMethod as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif

    <h2 class="text-lg font-bold mt-6">Clientes por Alertas</h2>
    @if ($getCustomersByAlert->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($getCustomersByAlert as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif


    <h2 class="text-lg font-bold mt-6">Clientes por Vendedor</h2>
    @if ($getCustomersBySeller->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($getCustomersBySeller as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif

    <h2 class="text-lg font-bold mt-6">Clientes por Segmentación</h2>
    @if ($getCustomersBySegmentation->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($getCustomersBySegmentation as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif

    <h2 class="text-lg font-bold mt-6">Clientes por Tienda</h2>
    @if ($customersByShop->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($customersByShop as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif

    <h2 class="text-lg font-bold mt-6">Clientes por Ciudad</h2>
    @if ($customersByCity->isEmpty())
        <p>No se encontraron clientes para esta selección.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach ($customersByCity as $customer)
                <li>{{ $customer->name }} ({{ $customer->email }})</li>
            @endforeach
        </ul>
    @endif


</div>
