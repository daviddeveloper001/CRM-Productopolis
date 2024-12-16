<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\Segmentation;
use App\Models\SalesComparative;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class ReportViewPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false;


    public $record;
    protected $customersWithNewSales = [];
    protected $customersWithRecentSales = [];
    protected $salesData;

    protected static string $view = 'filament.pages.report-view-page';

    public function mount($record)
    {
        $segment = Segmentation::find($record);
        $this->processCustomerSales($segment->customers);
    }


    private function processCustomerSales($customers)
    {
        SalesComparative::truncate(); // (Opcional) Elimina datos antiguos antes de procesar nuevos registros
    
        foreach ($customers as $customer) {
            $lastSale = Sale::where('customer_id', $customer->id)
                ->orderBy('date_last_order', 'desc')
                ->first();
                
                dd($lastSale);
            // Si no hay ventas relacionadas, continuar con el siguiente cliente
            if (!$lastSale) {
                continue;
            }
    
            // Inicializar valores "Antes"
            $totalSalesBefore = 0;
            $totalRevenuesBefore = 0;
            $returnValueBefore = 0;
            $ordersNumberBefore = 0;
            $deliveredBefore = 0;
            $returnsNumberBefore = 0;
    
            // Iterar sobre todas las ventas del cliente para obtener los valores "Antes"
            foreach ($customer->sales as $sale) {
                if ($lastSale->date_last_order > $sale->date_last_order) { // Solo considerar ventas anteriores a la última venta
                    $totalSalesBefore++;
                    $totalRevenuesBefore += $sale->total_revenues;
                    $returnValueBefore += $sale->return_value;
                    $ordersNumberBefore++;
                    if ($sale->status === 'delivered') {
                        $deliveredBefore++;
                    }
                    if ($sale->status === 'returned') {
                        $returnsNumberBefore++;
                    }
                }
            }
    
            // Crear un nuevo registro en SalesComparative
            SalesComparative::create([
                'client_name' => $customer->first_name,
    
                // Campos "Antes"
                'sales_before' => $totalSalesBefore,
                'revenues_before' => $totalRevenuesBefore,
                'returns_before' => $returnValueBefore,
                'orders_before' => $ordersNumberBefore,
                'delivered_before' => $deliveredBefore,
                'returns_number_before' => $returnsNumberBefore,
    
                // Campos "Después" tomados directamente de $lastSale
                'sales_after' => 1, // La última venta cuenta como una venta después
                'revenues_after' => $lastSale->total_revenues ?? 0,
                'returns_after' => $lastSale->return_value ?? 0,
                'orders_after' => 1, // Considerar la última venta como un solo pedido
                'delivered_after' => $lastSale->status === 'delivered' ? 1 : 0,
                'returns_number_after' => $lastSale->status === 'returned' ? 1 : 0,
            ]);
        }
    }
    
    public function report()
    {
        $customers = $this->segment->customers;

        dd($customers);
        $cont = 0;
        foreach ($customers as $customer) {


            $customerlastSale = Sale::where('customer_id', $customer->id)
                ->orderBy('date_last_order', 'desc')
                ->first();

            dd($customerlastSale);
            foreach ($customer->sales as $sale) {
                if ($customerlastSale && $customerlastSale->date_last_order > $sale->date_last_order) {
                    $cont++;
                    dd($cont);
                    continue;
                }
            }
        }

        dd($this->record);

        // Realiza el procesamiento necesario.
        $customers = $this->record->customers;
        // Por ejemplo, calcula algo sobre ventas del cliente.
    }



    public function table(Table $table): Table
    {
        return $table
            ->query(
                SalesComparative::query()
            )
            ->columns([
                TextColumn::make('client_name')
                    ->label('Cliente')
                    ->sortable(),
                TextColumn::make('sales_before')
                    ->label('Ventas Antes')
                    ->sortable(),
                TextColumn::make('sales_after')
                    ->label('Ventas Después')
                    ->sortable(),
                TextColumn::make('revenues_before')
                    ->label('Ingresos Antes')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('revenues_after')
                    ->label('Ingresos Después')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('returns_before')
                    ->label('Valor Devoluciones Antes')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('returns_after')
                    ->label('Valor Devoluciones Después')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('orders_before')
                    ->label('Órdenes Antes')
                    ->sortable(),
                TextColumn::make('orders_after')
                    ->label('Órdenes Después')
                    ->sortable(),
                TextColumn::make('delivered_before')
                    ->label('Entregadas Antes')
                    ->sortable(),
                TextColumn::make('delivered_after')
                    ->label('Entregadas Después')
                    ->sortable(),
                TextColumn::make('returns_number_before')
                    ->label('Devoluciones Antes')
                    ->sortable(),
                TextColumn::make('returns_number_after')
                    ->label('Devoluciones Después')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([]);
    }
}
