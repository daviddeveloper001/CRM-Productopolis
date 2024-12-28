<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Block;
use App\Enum\EventEnum;
use App\Models\Customer;
use App\Models\Segmentation;
use Illuminate\Http\Request;
use App\Enum\TypeCampaignEnum;
use App\Services\CityServices;
use App\Services\EventService;
use App\Models\SegmentRegister;
use App\Services\CountryServices;
use App\Services\CustomerServices;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;
use App\Factory\CampaignActionFactory;
use App\Jobs\ProcessConsultationMedical;
use App\Jobs\ProcessConsultationProductoPolis;
use App\Models\Segment;

class BlockController extends Controller
{
    public function __construct(protected CityServices $cityServices, protected DepartmentServices $departmentServices, protected CountryServices $countryServices, protected CustomerServices $customerServices, protected EventService $eventServices) {}
    public function index()
    {


        // Fecha actual redondeada al minuto
        $now = Carbon::now()->floorMinute(); // Ejemplo: 2024-12-16 16:23:00

        // Fecha actual + 2 minutos
        $upperLimit = $now->copy()->addMinutes(2);

        // Obtener bloques dentro del rango de tiempo actual y +2 minutos

        //$blocks = Block::all();
        $blocks = Block::where('id', 84)->get() /* Block::whereBetween('start_date', [$now, $upperLimit])->get() */;


        //dd($blocks);

        if ($blocks->isEmpty()) {

            dd('no hay datos');
            Log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }


        foreach ($blocks as $block) {
            Log::info($block->id);


            $campaign = $block->campaign;

            $typeCampaign = CampaignActionFactory::getAction($campaign->type_campaign);


            if ($typeCampaign) {
                try {
                    $typeCampaign->executeCampaign($block);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }


            /* $segment = Segmentation::create([
                'block_id' => $block->id
            ]);

            $campaign = $block->campaign;

            if ($campaign->type_campaign == TypeCampaignEnum::Medical->value) {
                $action = BlockActionFactory::getAction($block->exit_criterion);

                $country = $campaign->filters['country'];
                $isLead = $campaign->filters['is_lead'];
                $exists = $campaign->filters['exists'] ? '1' : '0';
                $createdSince = $campaign->filters['created_since'];
                $startDate = $campaign->filters['start_date'];
                $endDate = $campaign->filters['end_date'];
                $nextStepExecuted = $campaign->filters['next_step_executed'];

                if ($action) {
                    try {
                        $action->execute($block, [
                            'country' => $country,
                            'is_lead' => $isLead,
                            'exists' => $exists,
                            'created_since' => $createdSince,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'next_step_executed' => $nextStepExecuted
                        ]);
                        Log::info("Acción ejecutada para el bloque: {$block->id}");
                    } catch (\Exception $e) {
                        Log::error("Error al ejecutar la acción para el bloque {$block->id}: {$e->getMessage()}");
                    }
                } else {
                    Log::warning("No se encontró acción para el criterio: {$block->exit_criterion}");
                }
            }


            if ($campaign->type_campaign == TypeCampaignEnum::ProductoPolis->value) {

                $query = Customer::with(['sales', 'sales.paymentMethod', 'sales.shop', 'sales.seller', 'sales.returnAlert', 'sales.segmentType']);


                $filters = [
                    'payment_method_id' => $campaign->filters['payment_method_id'] ?? null,
                    'return_alert_id'   => $campaign->filters['alert'] ?? null,
                    'department_id'     => $campaign->filters['department_id'] ?? null,
                    'city_id'           => $campaign->filters['city_id'] ?? null,
                    'seller_id'         => $campaign->filters['seller_id'] ?? null,
                    'shop_id'           => $campaign->filters['shop_id'] ?? null,
                    'segment_type_id'   => $campaign->filters['segment_type_id'] ?? null,
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

                $data = $query->get();


                foreach ($data as $customer) {
                    SegmentRegister::create([
                        'segment_id' => $segment->id,
                        'customer_id' => $customer->id,
                    ]);
                }
            } */
        }


        Log::info('Procesamiento de bloques completado.');

        return response()->json(['message' => 'Procesamiento completado'], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        //
    }
}
