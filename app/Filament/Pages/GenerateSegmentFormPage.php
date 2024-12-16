<?php

namespace App\Filament\Pages;

use App\Models\Campaign;
use App\Models\City;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Seller;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Customer;
use Filament\Pages\Page;
use App\Models\Department;
use App\Models\ReturnAlert;
use App\Models\Segmentation;
use Filament\Actions\Action;
use App\Models\PaymentMethod;
use App\Models\SegmentRegister;
use Faker\Provider\ar_EG\Payment;
use App\Models\SegmentionRegister;
use App\Models\SegmentType;
use App\Utils\FormatUtils;
use Filament\Forms\Components\Placeholder;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class GenerateSegmentFormPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.generate-segment-form-page';

    protected static ?string $title = 'Generar segmento';

    public $is_unique = true;

    //protected static ?string $model = Sale::class;

    public $formData = [];


    public function mount()
    {
        $this->formData = [];
    }

    public function submit()
    {

        // Ejecutar la consulta
        //$query = Sale::with(['customer', 'paymentMethod', 'shop', 'seller', 'returnAlert']);

        $query = Customer::with(['sales', 'sales.paymentMethod', 'sales.shop', 'sales.seller', 'sales.returnAlert', 'sales.segmentType']);



        $filters = [
            'payment_method_id' => $this->formData['payment_method_id'] ?? null,
            'return_alert_id'   => $this->formData['alert'] ?? null,
            'department_id'     => $this->formData['department_id'] ?? null,
            'city_id'           => $this->formData['city_id'] ?? null,
            'seller_id'         => $this->formData['seller_id'] ?? null,
            'shop_id'           => $this->formData['shop_id'] ?? null,
            'segment_type_id'   => $this->formData['segment_type_id'] ?? null,
        ];


        $query->whereHas('sales', function ($salesQuery) use ($filters) {
            foreach ($filters as $column => $value) {
                if (!is_null($value)) {
                    // Dependiendo del filtro, aplicar las condiciones correctas de forma específica
                    if (in_array($column, ['payment_method_id', 'return_alert_id', 'shop_id', 'seller_id'])) {
                        $salesQuery->where($column, $value); // Campos que pertenecen al modelo Sale
                    }
                }
            }
        });




        /* $daysFromPurchase = $this->formData['days_from_purchase'] ?? null;
        $upDaysFromPurchase = $this->formData['up_to_days_from_purchase'] ?? null;

        if (!is_null($daysFromPurchase) && !is_null($upDaysFromPurchase)) {
            $query->whereBetween('created_at', [now()->subDays($upDaysFromPurchase), now()->subDays($daysFromPurchase)]);
        }

        $limit = $this->formData['limit'] ?? null;

        if (!is_null($limit)) {
            $query->limit($limit);
        } */


        $limit = $this->formData['limit'] ?? null;

        //dd($limit);

        if (!is_null($limit)) {
            $query->limit($limit);
        }



        // Obtener los datos
        $data = $query->get();

        $segment = Segmentation::create([
            'name' => $this->formData['name_segment'],
            'campaign_id' => $this->formData['campaign_id']
        ]);

        foreach ($data as $customer) {
            SegmentRegister::create([
                'segment_id' => $segment->id,
                'customer_id' => $customer->id,
            ]);
        }

        Notification::make()
            ->title('Segmento generado correctamente')
            ->success()
            ->send();

        return redirect()->route('table-segmentations');
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
                    'getNameForm' => $this->getNameForm(),

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
                    'customersByCity' => [
                        'query' => $this->getCustomersByCity()['query'],
                        'cities' => $this->getCustomersByCity()['cities']
                    ],
                    'customersByDepartment' => [
                        'query' => $this->getCustomersByDepartment()['query'],
                        'departments' => $this->getCustomersByDepartment()['departments']
                    ],
                    'customersBySegmentType' => [
                        'query' => $this->getCustomersBySegmentType()['query'],
                        'segmentTypes' => $this->getCustomersBySegmentType()['segmentTypes']
                    ]
                ]
            ))

            ->modalHeading('Información del segmento')
            ->modalSubmitActionLabel('Sí, generar')
            ->modalCancelActionLabel('Cancelar');
    }

    protected function getNameForm()
    {
        $segmentName = $this->formData["name_segment"] ?? null;

        return $segmentName;
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
            ->with('customer.sales.paymentMethod') // Cargar la relación con clientes
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
            ];
        }


        $query = Sale::where('seller_id', $sellerId)
            ->with('customer')
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'sellers' => $sellers,
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

    protected function getCustomersByDepartment()
    {
        // Obtener el ID del departamento desde el formulario
        $departmentId = $this->formData["department_id"] ?? null;

        // Si no hay departamento seleccionado, devolver una colección vacía
        if (is_null($departmentId)) {
            return [
                'query' => collect(),
                'departments' => collect(), // Puedes incluir una lista de departamentos si lo necesitas
            ];
        }

        // Consultar el departamento y cargar las ciudades con sus clientes y ventas
        $department = Department::with(['cities.customers.sales' => function ($query) {
            $query->distinct('customer_id'); // Ventas únicas por cliente
        }])->find($departmentId);

        if (!$department) {
            return [
                'query' => collect(),
                'departments' => collect(),
            ];
        }

        // Preparar el conteo de clientes únicos por departamento
        $customers = collect();
        $department->cities->each(function ($city) use (&$customers) {
            $city->customers->each(function ($customer) use (&$customers) {
                if ($customer->sales->isNotEmpty()) {
                    $customers->push($customer);
                }
            });
        });

        $uniqueCustomers = $customers->unique('id'); // Clientes únicos por ID

        // Contar los clientes únicos
        $customersCount = $uniqueCustomers->count();

        // Retornar los resultados
        return [
            'query' => $uniqueCustomers, // Lista de clientes únicos que han comprado
            'departments' => [
                [
                    'name' => $department->name,
                    'customers_count' => $customersCount,
                ],
            ], // Información para mostrar clientes únicos por departamento
        ];
    }



    protected function getCustomersBySegmentType()
    {
        $segmentTypeId = $this->formData["segment_type_id"] ?? null;

        $segmentTypes = SegmentType::withCount(['sales as customers_count' => function ($query) {
            $query->distinct('customer_id');
        }])->get();


        if (is_null($segmentTypeId)) {
            return [
                'query' => collect(),
                'segmentTypes' => $segmentTypes,
            ]; // Retorna una colección vacía pero con las segmentaciones.
        }

        // Obtener los clientes específicos de la segmentación seleccionada
        $query = Sale::where('segment_type_id', $segmentTypeId)
            ->with('customer.sales.segmentType') // Cargar la relación con clientes
            ->get()
            ->pluck('customer');

        return [
            'query' => $query,
            'segmentTypes' => $segmentTypes,
        ];
    }


    protected function getFormSchema(): array
    {
        return [
            Section::make('Información del segmento')
                ->columns([
                    'sm' => 3,
                    'xl' => 4,
                    '2xl' => 8,
                ])
                ->schema([
                    TextInput::make('name_segment')
                        ->label('Nombre del Segmento')
                        ->required()
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (callable $set, ?string $state) {
                            try {
                                if ($state && trim($state) !== '') {
                                    $exists = Segmentation::where('name', $state)->exists();
                                    $set('is_unique', !$exists);
                                }
                            } catch (\Exception $e) {
                                $set('is_unique', false);
                            }
                        })
                        ->helperText(fn($get) => $get('is_unique') === false
                            ? 'Este nombre ya está registrado. Por favor, elija otro.'
                            : '')
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                    Select::make('campaign_id')
                        ->label('Campaña')
                        //->searchable()
                        ->preload()
                        ->options(Campaign::all()->pluck('name', 'id'))
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),
                ]),
            Section::make('Filtros de segmentación')
                ->columns([
                    'sm' => 3,
                    'xl' => 4,
                    '2xl' => 8,
                ])
                ->hidden(fn(Get $get): bool => ! $get('name_segment'))
                ->schema([
                    /* TextInput::make('days_from_purchase')
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
                        ]), */
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
                    Select::make('segment_type_id')
                        ->label('Tipo de Segmento')
                        //->searchable()
                        ->preload()
                        ->options(SegmentType::all()->pluck('name', 'id'))
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
