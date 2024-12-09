<?php

namespace App\Filament\Pages;

use App\Helpers\EvolutionAPI;
use App\Mail\SegmentEmail;
use App\Models\City;
use App\Models\Config;
use App\Models\Sale;
use Filament\Pages\Page;
use App\Models\Department;
use Filament\Tables\Table;
use App\Models\Segmentation;
use App\Models\SegmentRegister;
use App\Models\Template;
use App\Utils\FormatUtils;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Contracts\HasTable;  // Añade esta interfaz
use Filament\Tables\Concerns\InteractsWithTable; // Añade este trait
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class TableSegmentionsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.table-segmentions-page';

    protected static ?string $title = 'Tabla de Segmentaciones';
    protected static ?string $model = Sale::class;


    public $data;

    /* public function mount()
    {
        $this->data = SegmentRegister::latest()->with(['segment', 'sale.customer', 'sale.shop', 'sale.seller', 'sale.paymentMethod', 'sale.returnAlert'])->get();

    } */

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SegmentRegister::query()
                    ->latest()
                    ->with(['segment', 'sale.customer', 'sale.shop', 'sale.seller', 'sale.paymentMethod', 'sale.returnAlert'])
            )
            ->columns([
                TextColumn::make('segment.type')
                    ->label('Seg'),
                TextColumn::make('sale.customer.customer_name')
                    ->label('Cliente'),
                TextColumn::make('sale.customer.first_name')
                    ->label('Primer Nombre'),
                TextColumn::make('sale.customer.phone')
                    ->label('Telefón'),
                TextColumn::make('sale.customer.email')
                    ->label('Correo'),
                TextColumn::make('sale.customer.city.name')
                    ->label('Ciudad'),
                TextColumn::make('sale.customer.city.department.name')
                    ->label('Departamento'),
                TextColumn::make('sale.orders_number')
                    ->label('Ordenes'),
                TextColumn::make('sale.number_entries')
                    ->label('Entradas'),
                TextColumn::make('sale.returns_number')
                    ->label('Devoluciones'),
                TextColumn::make('sale.last_order_date_delivered')
                    ->label('Fecha de entrega'),
                TextColumn::make('sale.last_order_date_delivered')
                    ->label('Fecha de entrega'),
                TextColumn::make('sale.last_order_date_delivered')
                    ->label('Fecha de entrega'),
                TextColumn::make('sale.total_revenues')
                    ->label('Ingresos'),
                TextColumn::make('sale.return_value')
                    ->label('Valor devoluciones'),
                TextColumn::make('sale.paymentMethod.name')
                    ->label('Método de pago'),
                TextColumn::make('sale.seller.name')
                    ->label('Vendedor'),
                TextColumn::make('sale.customer.is_frequent_customer')
                    ->label('Es común'),
                TextColumn::make('sale.shop.name')
                    ->label('Tienda'),
                TextColumn::make('sale.last_item_purchased')
                    ->label('Último item comprado'),
                TextColumn::make('sale.last_item_purchased')
                    ->label('Antepenúltimo item comprado'),
                TextColumn::make('sale.last_days_purchase_days')
                    ->label('Días desde última compra'),
                TextColumn::make('sale.returnAlert.type')
                    ->label('Alerta de Devolución'),
            ])
            ->filters([
                SelectFilter::make('segmentation.type')
                    ->label('Seg')
                    ->relationship('sale.segmentation', 'type'),
                SelectFilter::make('sale.customer.customer_name')
                    ->label('Cliente'),
                SelectFilter::make('sale.customer.city.name')
                    ->label('Ciudad')
                    ->relationship('sale.customer.city', 'name'),
                SelectFilter::make('sale.customer.city.department.name')
                    ->label('Departamento')
                    ->options(Department::all()->pluck('name', 'name')),
            ])
            ->bulkActions([
                BulkAction::make('send_email')
                    ->color('success')
                    ->label('Enviar Correo')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        // Iterar sobre cada registro seleccionado
                        $records->each(function ($segmentRegister) {
                            // Asegúrate de cargar las relaciones
                            $segmentRegister->load('sale.customer');

                            $email = $segmentRegister->sale->customer->email;

                            if ($email) {
                                Mail::to($email)->send(new SegmentEmail());
                            }

                            //dd('correo' . $email);

                            /* foreach ($segmentRegister->sale as $item) {

                                $customer = $item->customer;
            
                                if ($customer) {
                                    dd('correo enviado');
                                }
                            } */
                        });
                    }),

                BulkAction::make('send_email_with_template')
                    ->label('Enviar Mensaje')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('template')
                            ->label('Plantilla')
                            ->options(Template::where('type', 'whatsapp')->pluck('name', 'id'))
                            ->required()
                            ->columnSpan([
                                'sm' => 12,
                                'xl' => 12,
                                '2xl' => 12,
                            ])
                            ->live(),
                        Select::make('preview_with')
                            ->label('Previsualizar con')
                            ->options(function () {
                                return Sale::all()->mapWithKeys(function ($sale) {
                                    return [$sale->id => 'Venta #' . $sale->id];
                                });
                            })
                            ->columnSpan([
                                'sm' => 12,
                                'xl' => 12,
                                '2xl' => 12,
                            ])
                            ->live(),
                        Placeholder::make('preview')
                            ->content(function ($get) {
                                $template = Template::find($get('template'));
                                $saleId = $get('preview_with');

                                if (!$template) {
                                    return new HtmlString('Selecciona una plantilla');
                                }

                                return new HtmlString(
                                    FormatUtils::replaceSalePlaceholders(
                                        FormatUtils::parseWhatsAppFormatting($template->content),
                                        $saleId
                                    )
                                );
                            })
                            ->columnSpan([
                                'sm' => 12,
                                'xl' => 12,
                                '2xl' => 12,
                            ]),
                        FileUpload::make('attachment')
                            ->label('Adjunto')
                            ->acceptedFileTypes(['image/*', 'audio/*', 'application/pdf'])
                            ->maxSize(10240)
                            ->columnSpan([
                                'sm' => 12,
                                'xl' => 12,
                                '2xl' => 12,
                            ])
                            ->live(),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $batch = Config::where('key_', 'SEGMENTATION_BATCH')->first()->value;

                        if ($batch == 1) {

                            $batchSize = is_numeric(Config::where('key_', 'SEGMENTATION_BATCH_SIZE')->first()->value) ? (int) Config::where('key_', 'SEGMENTATION_BATCH_SIZE')->first()->value : 10;
                            $batchDelay = is_numeric(Config::where('key_', 'SEGMENTATION_BATCH_DELAY')->first()->value) ? (float) Config::where('key_', 'SEGMENTATION_BATCH_DELAY')->first()->value : 0.3;
                            $messageDelay = is_numeric(Config::where('key_', 'SEGMENTATION_BATCH_MESSAGE_DELAY')->first()->value) ? (float) Config::where('key_', 'SEGMENTATION_BATCH_MESSAGE_DELAY')->first()->value : 0.3;

                            $records->chunk($batchSize)->each(function ($chunk, $index) use ($data, $batchSize, $batchDelay, $messageDelay, $records) {
                                $chunk->each(function ($record) use ($data, $messageDelay) {

                                    $dataToSend = [
                                        'phone' => $record->sale->customer->phone,
                                        'message' => FormatUtils::replaceSalePlaceholders(
                                            Template::find($data['template'])->content,
                                            $record->sale->id
                                        ),
                                        'filename' => isset($data['attachment']) ? $data['attachment'] : "",
                                        'attachment_url' => isset($data['attachment']) ? url('storage/' . $data['attachment']) : "",
                                    ];

                                    EvolutionAPI::whatsapp_send_message_EA(
                                        $dataToSend['filename'],
                                        /*$dataToSend['attachment_url'],*/
                                        "https://olondriz.com/wp-content/uploads/2020/04/ambar-perrito-1.jpg",
                                        $dataToSend['phone'],
                                        $dataToSend['message'],
                                        'SALES'
                                    );

                                    sleep($messageDelay);
                                });

                                if ($index < $records->chunk($batchSize)->count() - 1) {
                                    sleep($batchDelay);
                                }
                            });
                        } else {
                            foreach ($records as $record) {
                                $dataToSend = [
                                    'phone' => $record->sale->customer->phone,
                                    'message' => FormatUtils::replaceSalePlaceholders(
                                        Template::find($data['template'])->content,
                                        $record->sale->id
                                    ),
                                    'filename' => isset($data['attachment']) ? $data['attachment'] : "",
                                    'attachment_url' => isset($data['attachment']) ? url('storage/' . $data['attachment']) : "",
                                ];

                                EvolutionAPI::whatsapp_send_message_EA(
                                    $dataToSend['filename'],
                                    /*$dataToSend['attachment_url'],*/
                                    "https://olondriz.com/wp-content/uploads/2020/04/ambar-perrito-1.jpg",
                                    $dataToSend['phone'],
                                    $dataToSend['message'],
                                    'SALES'
                                );
                            }
                        }
                    }),
            ])
            ->headerActions([
                Action::make('Enviar Correo')
                    ->label('Enviar Correo')
            ]);
    }
}
