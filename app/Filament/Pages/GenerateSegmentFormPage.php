<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Shop;
use App\Models\Seller;
use App\Enum\AlertEnum;
use Filament\Pages\Page;
use App\Models\Department;
use App\Models\ReturnAlert;
use App\Models\Segmentation;
use App\Models\PaymentMethod;
use App\Enum\PaymentMethodEnum;
use App\Models\Sale;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class GenerateSegmentFormPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.generate-segment-form-page';

    protected static ?string $title = 'Generar segmento';

    public $formData = [];

    public function mount()
    {
        $this->formData = [];
    }

    public function submit(): void
    {
        // Captura y limpieza de los datos del formulario
        $daysFromPurchase = $this->formData["days_from_purchase"] ?? null;
        $upDaysFromPurchase = $this->formData["up_to_days_from_purchase"] ?? null;
        $paymentMethodId = $this->formData["payment_method_id"] ?? null;
        $alertId = $this->formData["alert"] ?? null;
        $departmentId = $this->formData["department_id"] ?? null;
        $cityId = $this->formData["city_id"] ?? null;
        $limit = $this->formData["limit"] ?? null;
        $segmentationId = $this->formData["segmentation_id"] ?? null;
        $sellerId = $this->formData["seller_id"] ?? null;
        $shopId = $this->formData["shop_id"] ?? null;
    
        // Construir la consulta dinámicamente
        $query = Sale::with(['customer', 'paymentMethod', 'shop', 'seller', 'segmentation', 'returnAlert']);
    
        // Agrega solo las cláusulas que tienen valor
        if (!is_null($paymentMethodId)) {
            $query->where('payment_method_id', $paymentMethodId);
        }
    
        if (!is_null($alertId)) {
            $query->where('return_alert_id', $alertId);
        }
    
        if (!is_null($departmentId)) {
            $query->where('department_id', $departmentId);
        }
    
        if (!is_null($cityId)) {
            $query->where('city_id', $cityId);
        }
    
        if (!is_null($segmentationId)) {
            $query->where('segmentation_id', $segmentationId);
        }
    
        if (!is_null($sellerId)) {
            $query->where('seller_id', $sellerId);
        }
    
        if (!is_null($shopId)) {
            $query->where('shop_id', $shopId);
        }
    
        // Filtro por días desde la compra (rango de fechas)
        if (!is_null($daysFromPurchase) && !is_null($upDaysFromPurchase)) {
            $query->whereBetween('created_at', [now()->subDays($upDaysFromPurchase), now()->subDays($daysFromPurchase)]);
        }
    
        // Aplica un límite si se especifica
        if (!is_null($limit)) {
            $query->limit($limit);
        }
    
        // Ejecuta la consulta y obtiene los resultados
        $sales = $query->get();
    
        // Depurar resultados
        dd($sales);
    }
    

    protected function getFormSchema(): array
    {
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
