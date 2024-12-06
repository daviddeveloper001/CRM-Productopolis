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
                    TextInput::make('dias_desde_compra')
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
                    Select::make('method')
                        ->label('Método de pago')
                        ->options(PaymentMethod::all()->pluck('method', 'id'))
                        //->searchable()
                        ->preload()
                        ->columnSpan([
                            'sm' => 2,
                            'xl' => 3,
                            '2xl' => 4,
                        ]),

                    Select::make('alert_id')
                        ->label('Alertas de devolución')
                        ->enum(AlertEnum::class)
                        ->options(AlertEnum::class)
                        //->options(ReturnAlert::all()->pluck('status', 'id'))
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
