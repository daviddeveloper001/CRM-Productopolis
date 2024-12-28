<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use Filament\Tables;
use App\Models\Block;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\Customer;
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

    public $campaign;
    public $blocks;
    public $customers;
    public $currentBlockId;
    public $block;

    protected static string $view = 'filament.pages.report-view-page';

    public function mount($record)
    {

        $this->campaign = Campaign::find($record);
        $this->blocks = $this->campaign->blocks()->with('segment.customers')->get();
        /* foreach ($this->blocks as $block) {
            dd($block->segment->customers);
        }

         */
        $this->block = Block::with('segment.customers')->findOrFail(85);
        /* foreach ($this->blocks as $block) {

        } */


    }


    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->block->segment->customers()->getQuery()
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID del Cliente'),
                TextColumn::make('first_name')
                    ->label('Nombre del Cliente'),
                TextColumn::make('last_name')
                    ->label('Apellido del Cliente'),
            ])
            ->filters([])
            ->actions([]);
    }

    private function processCustomerSales($customers)
    {
        SalesComparative::truncate(); // (Opcional) Elimina datos antiguos antes de procesar nuevos registros

        $cont = 0;
        foreach ($customers as $customer) {
            $lastSale = Sale::where('customer_id', $customer->id)
                ->orderBy('date_last_order', 'desc')
                ->first();

            //dd($lastSale);
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
}
