<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Seller;
use App\Enum\AlertEnum;
use Filament\Pages\Page;
use App\Models\Department;
use App\Models\ReturnAlert;
use App\Models\Segmentation;
use Filament\Actions\Action;
use App\Models\PaymentMethod;
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
                    'customersByPaymentMethod' => $this->getCustomersByPaymentMethod(),
                    'customersByShop' => $this->getCustomersByShop(),
                    'getCustomersByAlert' => $this->getCustomersByAlert(),
                    'getCustomersBySeller' => $this->getCustomersBySeller(),
                    'getCustomersBySegmentation' => $this->getCustomersBySegmentation(),
                    'customersByCity' => $this->getCustomersByCity(),
                ]
            ))
            ->modalHeading('Información del segmento')
            ->modalSubmitActionLabel('Sí, generar')
            ->modalCancelActionLabel('Cancelar');
    }

    protected function getCustomersByPaymentMethod()
    {
        $paymentMethodId = $this->formData["payment_method_id"] ?? null;

        if (is_null($paymentMethodId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        // Filtramos las ventas por método de pago y obtenemos los clientes
        return Sale::where('payment_method_id', $paymentMethodId)
            ->with('customer') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
    }

    protected function getCustomersByAlert()
    {
        $alertId = $this->formData["alert_id"] ?? null;

        if (is_null($alertId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        // Filtramos las ventas por método de pago y obtenemos los clientes
        return Sale::where('return_alert_id', $alertId)
            ->with('customer') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
    }

    protected function getCustomersBySeller()
    {
        $sellerId = $this->formData["seller_id"] ?? null;

        if (is_null($sellerId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        // Filtramos las ventas por tienda y obtenemos los clientes
        return Sale::where('seller_id', $sellerId)
            ->with('customer') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
    }



    protected function getCustomersBySegmentation()
    {
        $segmentationId = $this->formData["segmentation_id"] ?? null;

        if (is_null($segmentationId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        // Filtramos las ventas por tienda y obtenemos los clientes
        return Sale::where('segmentation_id', $segmentationId)
            ->with('customer') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
    }


    protected function getCustomersByShop()
    {
        $shopId = $this->formData["shop_id"] ?? null;

        if (is_null($shopId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        // Filtramos las ventas por tienda y obtenemos los clientes
        return Sale::where('shop_id', $shopId)
            ->with('customer') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
    }


    protected function getCustomersByCity()
    {
        $cityId = $this->formData["city_id"] ?? null;

        if (is_null($cityId)) {
            return collect(); // Si no hay selección, devolver colección vacía.
        }

        $city = City::where('id', $cityId)->first();

        $city->customers;

        // Filtramos las ventas por tienda y obtenemos los clientes
        return Sale::where('customer_id', $cityId)
            ->with('customer.city') // Nos aseguramos de cargar la relación con clientes
            ->get()
            ->pluck('customer'); // Obtenemos solo los clientes
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
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Departamento')
                                ->required(),
                        ])
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
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Ciudad')
                                ->required(),
                        ])
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
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Método de pago')
                                ->required(),
                        ])
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
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Vendedor')
                                ->required(),
                        ])
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
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Tienda')
                                ->required(),
                        ])
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
