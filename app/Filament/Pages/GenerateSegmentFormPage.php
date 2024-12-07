<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Seller;
use Filament\Pages\Page;
use App\Models\Department;
use App\Models\ReturnAlert;
use App\Models\Segmentation;
use Filament\Actions\Action;
use App\Models\PaymentMethod;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Collection;

class GenerateSegmentFormPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.generate-segment-form-page';

    protected static ?string $title = 'Generar segmento';

    //protected static ?string $model = Sale::class;

    public $formData = [];

    public function mount()
    {
        $this->formData = [];
    }

    public function submit(): Collection
    {
        // Inicializar consulta base con relaciones
        $query = Sale::with(['customer', 'paymentMethod', 'shop', 'seller', 'segmentation', 'returnAlert']);

        // Mapear filtros dinámicos y aplicarlos
        $filters = [
            'payment_method_id' => $this->formData['payment_method_id'] ?? null,
            'return_alert_id'   => $this->formData['alert'] ?? null,
            'department_id'     => $this->formData['department_id'] ?? null,
            'city_id'           => $this->formData['city_id'] ?? null,
            'segmentation_id'   => $this->formData['segmentation_id'] ?? null,
            'seller_id'         => $this->formData['seller_id'] ?? null,
            'shop_id'           => $this->formData['shop_id'] ?? null,
        ];

        foreach ($filters as $column => $value) {
            if (!is_null($value)) {
                $query->where($column, $value);
            }
        }

        // Filtrar por rango de días desde la compra
        $daysFromPurchase = $this->formData['days_from_purchase'] ?? null;
        $upDaysFromPurchase = $this->formData['up_to_days_from_purchase'] ?? null;

        if (!is_null($daysFromPurchase) && !is_null($upDaysFromPurchase)) {
            $query->whereBetween('created_at', [now()->subDays($upDaysFromPurchase), now()->subDays($daysFromPurchase)]);
        }

        // Aplicar límite si existe
        $limit = $this->formData['limit'] ?? null;

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        // Ejecutar la consulta y devolver los resultados
        return $query->get();
    }


    public function submitAction(): Action
    {
        return Action::make('submit')
            ->label('Generar segmento')
            ->action(function () {
                $this->submit();
            })
            ->modalContent(fn(): View => view(
                'filament.pages.modal-segmentation',
                [
                    'customersByPaymentMethod' => [
                        'query' => $this->getCustomersByPaymentMethod()['query'],
                        'payments' => $this->getCustomersByPaymentMethod()['payments']
                    ],
                    'customersByShop' => [
                        'query' => $this->getCustomersByShop()['query'],
                        'shops' => $this->getCustomersByShop()['shops']
                    ],
                    'getCustomersByAlert' => [
                        'query' => $this->getCustomersByAlert()['query'],
                        'alerts' => $this->getCustomersByAlert()['alerts']
                    ],
                    'getCustomersBySeller' => [
                        'query' => $this->getCustomersBySeller()['query'],
                        'sellers' => $this->getCustomersBySeller()['sellers']
                    ],
                    'getCustomersBySegmentation' => [
                        'query' => $this->getCustomersBySegmentation()['query'],
                        'segmentations' => $this->getCustomersBySegmentation()['segmentations']
                    ],
                    'customersByCity' => [
                        'query' => $this->getCustomersByCity()['query'],
                        'cities' => $this->getCustomersByCity()['cities']
                    ],
                ]
            ))
            ->modalHeading('Información del segmento')
            ->modalSubmitActionLabel('Sí, generar')
            ->modalCancelActionLabel('Cancelar');
    }

    protected function getCustomersByPaymentMethod()
    {
        $paymentMethodId = $this->formData["payment_method_id"] ?? null;

        $payments = PaymentMethod::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();

        if (is_null($paymentMethodId)) {
            return [
                'query' => collect(),
                'payments' => $payments,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        // Obtener los clientes específicos de la segmentación seleccionada
        $query = Sale::where('payment_method_id', $paymentMethodId)
            ->with('customer') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'payments' => $payments,
        ];
    }

    protected function getCustomersByAlert()
    {
        $alertId = $this->formData["alert_id"] ?? null;

        $alerts = ReturnAlert::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();

        if (is_null($alertId)) {
            return [
                'query' => collect(),
                'alerts' => $alerts,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        // Obtener los clientes específicos de la segmentación seleccionada
        $query = Sale::where('return_alert_id', $alertId)
            ->with('customer') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'alerts' => $alerts,
        ];
    }

    protected function getCustomersBySeller()
    {
        $sellerId = $this->formData["seller_id"] ?? null;

        $sellers = Seller::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();

        if (is_null($sellerId)) {
            return [
                'query' => collect(),
                'sellers' => $sellers,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        // Obtener los clientes específicos de la segmentación seleccionada
        $query = Sale::where('segmentation_id', $sellerId)
            ->with('customer') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'sellers' => $sellers,
        ];
    }



    protected function getCustomersBySegmentation()
    {
        $segmentationId = $this->formData["segmentation_id"] ?? null;

        $segmentations = Segmentation::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();

        if (is_null($segmentationId)) {
            return [
                'query' => collect(),
                'segmentations' => $segmentations,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        // Obtener los clientes específicos de la segmentación seleccionada
        $query = Sale::where('segmentation_id', $segmentationId)
            ->with('customer') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'segmentations' => $segmentations,
        ];
    }


    protected function getCustomersByShop()
    {

        $shopId = $this->formData["shop_id"] ?? null;

        $shops = Shop::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();

        if (is_null($shopId)) {
            return [
                'query' => collect(),
                'shops' => $shops,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        $query = Sale::where('shop_id', $shopId)
            ->with('customer') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'shops' => $shops,
        ];
    }


    protected function getCustomersByCity()
    {
        // Obtener el ID de la ciudad desde el formulario
        $cityId = $this->formData["city_id"] ?? null;

        // Si no hay ciudad seleccionada, devolver una colección vacía
        if (is_null($cityId)) {
            return [
                'query' => collect(),
                'cities' => collect(), // Puedes obtener una lista de ciudades si lo necesitas
            ];
        }

        // Consultar la ciudad y cargar la relación con sus clientes
        $city = City::with(['customers.sales' => function ($query) {
            $query->distinct('customer_id'); // Solo ventas únicas por cliente
        }])->find($cityId);

        if (!$city) {
            return [
                'query' => collect(),
                'cities' => collect(),
            ];
        }

        // Preparar el conteo de clientes únicos por ciudad
        $customersCount = $city->customers->filter(function ($customer) {
            return $customer->sales->isNotEmpty();
        })->count();

        // Obtener los clientes que han realizado compras
        $customers = Sale::whereIn('customer_id', $city->customers->pluck('id'))
            ->with('customer') // Cargar relación de cliente
            ->get()
            ->pluck('customer')
            ->unique('id'); // Clientes únicos

        return [
            'query' => $customers, // Lista de clientes que han comprado
            'cities' => [
                [
                    'name' => $city->name,
                    'customers_count' => $customersCount,
                ],
            ], // Información para mostrar clientes únicos por ciudad
        ];
    }





    protected function getFormSchema(): array
    {
        /* return Sale::getForm(); */
        return [
            Section::make()
                ->columns([
                    'sm' => 3,
                    'xl' => 4,
                    '2xl' => 8,
                ])
                ->schema([
                    TextInput::make('days_from_purchase')
                        ->label('Días desde Compra')
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                    TextInput::make('hasta_dias_desde_compra')
                        ->label('Hasta Días desde Compra')
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                    Select::make('payment_method_id')
                        ->label('Método de pago')
                        ->options(PaymentMethod::all()->pluck('name', 'id'))
                        //->searchable()
                        ->preload()
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    Select::make('alert_id')
                        ->label('Alertas de devolución')
                        ->options(ReturnAlert::all()->pluck('type', 'id'))
                        ->preload()
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                    Select::make('department_id')
                        ->label('Departamento')
                        //->searchable()
                        ->preload()
                        ->options(Department::all()->pluck('name', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    Select::make('city_id')
                        ->label('Ciudad')
                        //->searchable()
                        ->preload()
                        ->options(City::all()->pluck('name', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    TextInput::make('limit')
                        ->label('Limite')
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                    Select::make('segmentation_id')
                        ->label('Segmento')
                        //->searchable()
                        ->preload()
                        ->options(Segmentation::all()->pluck('type', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    Select::make('seller_id')
                        ->label('Vendedor')
                        //->searchable()
                        ->preload()
                        ->options(Seller::all()->pluck('name', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    Select::make('shop_id')
                        ->label('Tienda')
                        //->searchable()
                        ->preload()
                        ->options(Shop::all()->pluck('name', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                ]),
        ];
    }


    protected function getFormStatePath(): string
    {
        return 'formData';
    }
}
