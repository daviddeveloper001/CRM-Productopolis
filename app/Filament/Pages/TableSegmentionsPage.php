<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Sale;
use App\Models\Config;
use App\Models\Customer;
use App\Models\Template;
use Filament\Pages\Page;
use App\Mail\SegmentEmail;
use App\Models\Department;
use App\Utils\FormatUtils;
use Filament\Tables\Table;
use App\Models\Segmentation;
use App\Helpers\EvolutionAPI;
use App\Models\SegmentRegister;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Enums\Alignment;

class TableSegmentionsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.table-segmentions-page';

    protected static ?string $title = 'Tabla de Segmentaciones';
    protected static ?string $model = Segmentation::class;


    public $data;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Segmentation::query()
                    ->latest()
                    ->with(['customers', 'customers.sales'])
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('name')
                    ->label('Nombre segmento'),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->since()
                    ->dateTimeTooltip(),

                TextColumn::make('customers.customer_name')
                    ->label('Participantes')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customers->count();
                    }),

                TextColumn::make('customers.sales.total_sales')
                    ->label('Ventas')
                    ->formatStateUsing(function ($state, $record) {
                        $totalSales = 0;

                        foreach ($record->customers as $customer) {
                            $totalSales += collect($customer->sales)->sum('total_sales');
                        }

                        return '$ ' . number_format($totalSales, 2);
                    }),


                TextColumn::make('customers.sales.total_revenues')
                    ->label('Ingresos')
                    ->formatStateUsing(function ($state, $record) {
                        $totalRevenues = 0;

                        foreach ($record->customers as $customer) {
                            $totalRevenues += collect($customer->sales)->sum('total_revenues');
                        }

                        return '$ ' . number_format($totalRevenues, 2);
                    }),



                TextColumn::make('customers.sales.return_value')
                    ->label('Valor Devoluciones')
                    ->formatStateUsing(function ($state, $record) {
                        $totalReturnValues = 0;

                        foreach ($record->customers as $customer) {
                            $totalReturnValues += collect($customer->sales)->sum('return_value');
                        }

                        return '$ ' . number_format($totalReturnValues, 2);
                    }),



                /* TextColumn::make('customers.sales.returns_number')
                    ->label('Suma ingresos devoluciones'), */


                TextColumn::make('customers.sales.orders_number')
                    ->label('Ordenes')
                    ->formatStateUsing(function ($state, $record) {
                        $totalOrderNumbers = 0;

                        foreach ($record->customers as $customer) {
                            $totalOrderNumbers += collect($customer->sales)->sum('orders_number');
                        }

                        return $totalOrderNumbers;
                    }),

                TextColumn::make('customers.sales.delivered')
                    ->label('Entregadas')
                    ->formatStateUsing(function ($state, $record) {
                        $totalDelivereds = 0;

                        foreach ($record->customers as $customer) {
                            $totalDelivereds += collect($customer->sales)->sum('delivered');
                        }

                        return $totalDelivereds;
                    }),



                TextColumn::make('customers.sales.returns_number')
                    ->label('Devoluciones')
                    ->numeric()
                    ->formatStateUsing(function ($state, $record) {
                        $totalReturnNumbers = 0;

                        foreach ($record->customers as $customer) {
                            $totalReturnNumbers += collect($customer->sales)->sum('returns_number');
                        }

                        return $totalReturnNumbers;
                    }),

            ])
            ->filters([])
            ->actions([
                Action::make('Reporte')
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre')
                    ])
                    ->action(function ($record): void {
                        $customers = $record->customers;
                        $cont = 0;
                        foreach ($customers as $customer) {


                            $customerlastSale = Sale::where('customer_id', $customer->id)
                                ->orderBy('date_last_order', 'desc')
                                ->first();
                            foreach ($customer->sales as $sale) {
                                if ($customerlastSale && $customerlastSale->date_last_order > $sale->date_last_order) {
                                    $cont++;
                                    continue;
                                }
                            }
                        }
                    })
                    ->slideOver()
            ])
            ->bulkActions([
                BulkAction::make('send_email')
                    ->label('Enviar Correo')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('template')
                            ->label('Plantilla')
                            ->options(Template::where('type', 'email')->pluck('name', 'id'))
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
                                        $template->content,
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
                    /* ->action(function (Collection $records, array $data) {
                        foreach ($records as $segmentRegister) {
                            $segmentRegister->load('sale.customer');
                            $email = $segmentRegister->sale->customer->email;

                            if ($email) {
                                $template = Template::find($data['template']);
                                $messageContent = FormatUtils::replaceSalePlaceholders(
                                    $template->content,
                                    $segmentRegister->sale->id
                                );

                                $mailData = [
                                    'name' => $segmentRegister->sale->customer->customer_name,
                                    'message' => $messageContent,
                                    'attachment_url' => isset($data['attachment']) ? url('storage/' . $data['attachment']) : "",
                                ];

                                Mail::to($email)->send(new SegmentEmail($mailData, $data['attachment'] ?? null));
                            }

                            Notification::make()
                                ->title('Correo enviado exitosamente')
                                ->success()
                                ->send();
                        }
                    }), */
                    ->action(function (Collection $records, array $data) {
                        foreach ($records as $segmention) {

                            $segmention->load('customers');

                            $customers = $segmention->customers;
                            foreach ($customers as $customer) {
                                $email = $customer->email;
                                if ($email) {
                                    $template = Template::find($data['template']);

                                    $messageContent = FormatUtils::replaceSalePlaceholders(
                                        $template->content,
                                        $customer->sales->id
                                    );

                                    $mailData = [
                                        'name' => $segmention->sale->customer->customer_name,
                                        'message' => $messageContent,
                                        'attachment_url' => isset($data['attachment']) ? url('storage/' . $data['attachment']) : "",
                                    ];

                                    Mail::to($email)->send(new SegmentEmail($mailData, $data['attachment'] ?? null));
                                }

                                Notification::make()
                                    ->title('Correo enviado exitosamente')
                                    ->success()
                                    ->send();
                            }
                        }
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
                        Notification::make()
                            ->title('Mensaje enviado exitosamente')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
