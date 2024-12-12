<div>
    <div class="mb-4">
        <h3>{{ $getNameForm }}</h3>
        <div>@error('name_segment') {{ $message }} @enderror</div>
    </div>
    @if (!$customersByPaymentMethod['query']->isEmpty())
        <h2 class="text-lg font-bold">Clientes por Método de Pago</h2>

        <ul class="list-disc list-inside">

            @foreach ($customersByPaymentMethod['payments'] as $payment)
                @if ($payment->customers_count != 0)
                    <li>
                        <strong>{{ $payment->name }}</strong>:
                        {{ $payment->customers_count ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>
    @endif

    @if (!$getCustomersByAlert['query']->isEmpty())
        <h2 class="text-lg font-bold mt-6">Clientes por Alertas</h2>
        <ul class="list-disc list-inside">
            @foreach ($getCustomersByAlert['alerts'] as $alert)
                @if ($alert->customers_count != 0)
                    <li>
                        <strong>{{ $alert->type }}</strong>:
                        {{ $alert->customers_count ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>

    @endif


    @if (!$getCustomersBySeller['query']->isEmpty())
        <h2 class="text-lg font-bold mt-6">Clientes por Vendedor</h2>
        <ul class="list-disc list-inside">

            @foreach ($getCustomersBySeller['sellers'] as $seller)
                @if ($seller->customers_count != 0)
                    <li>
                        <strong>{{ $seller->name }}</strong>:
                        {{ $seller->customers_count ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>

    @endif


    @if (!$customersByShop['query']->isEmpty())
        <h2 class="text-lg font-bold mt-6">Clientes por Tiendas</h2>
        <ul class="list-disc list-inside">
            @foreach ($customersByShop['shops'] as $shop)
                @if ($shop->customers_count != 0)
                    <li>
                        <strong>{{ $shop->name }}</strong>:
                        {{ $shop->customers_count ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>
    @endif


    @if (!$customersByCity['query']->isEmpty())
        <h2 class="text-lg font-bold mt-6">Clientes por Ciudad</h2>
        <ul class="list-disc list-inside">
            @foreach ($customersByCity['cities'] as $city)
                @if ($city['customers_count'] != 0)
                    <li>
                        <strong>{{ $city['name'] }}</strong>:
                        {{ $city['customers_count'] ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>
    @endif

    @if (!$customersByDepartment['query']->isEmpty())
        <h2 class="text-lg font-bold mt-6">Clientes por Departamento</h2>
        <ul class="list-disc list-inside">
            @foreach ($customersByDepartment['departments'] as $department)
                @if ($department['customers_count'] != 0)
                    <li>
                        <strong>{{ $department['name'] }}</strong>:
                        {{ $department['customers_count'] ?? 0 }} clientes
                    </li>
                @endif
            @endforeach
        </ul>
    @endif

</div>
