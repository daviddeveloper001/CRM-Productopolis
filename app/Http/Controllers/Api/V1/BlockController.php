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
use App\Jobs\ProcessConsultationMedical;
use App\Jobs\ProcessConsultationProductoPolis;

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
        $blocks = Block::all() /* Block::whereBetween('start_date', [$now, $upperLimit])->get() */;


        if ($blocks->isEmpty()) {

            dd('no hay datos');
            Log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }


        foreach ($blocks as $block) {


            $segment = Segmentation::create([
                'block_id' => $block->id
            ]);

            $campaign = $block->campaign;

            if ($campaign->type_campaign == TypeCampaignEnum::Medical->value) {

                //ProcessConsultationMedical::dispatch($campaign);

                

                $country = $campaign->filters['country'];
                $isLead = $campaign->filters['is_lead'];
                $exists = $campaign->filters['exists'] ? '1' : '0';
                $createdSince = $campaign->filters['created_since'];
                $startDate = $campaign->filters['start_date'];
                $endDate = $campaign->filters['end_date'];
                $nextStepExecuted = $campaign->filters['next_step_executed'];

                try {

                    $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_scheduling_and_demo', [
                        'country' => $country,
                        'is_lead' => $isLead,
                        'exists' => $exists,
                        'created_since' => $createdSince,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'next_step_executed' => $nextStepExecuted
                    ]);


                    if ($response->successful()) {

                        $users = $response->json();


                        foreach ($users['data'] as $user) {


                            $department = $this->departmentServices->createDepartment($user['departamento'] ?? 'Default Department Name');
                            $city = $this->cityServices->createCity($user['ciudad'] ?? 'Default City Name', $department->id);

                            $country = $this->countryServices->createCountry($user['pais']);
                            $customer = $this->customerServices->createCustomer($user, $city->id, $country->id);

                            $customer->blocks()->syncWithoutDetaching([$block->id]);



                            $event = $this->eventServices->createEvent($user, $customer->id);


                            SegmentRegister::create([
                                'segment_id' => $segment->id,
                                'customer_id' => $customer->id,
                            ]);


                            //$this->processBlockSpecificLogic($block, $customer, $event);
                        }

                        /* foreach ($data as $customer) {
                            SegmentRegister::create([
                                'segment_id' => $segment->id,
                                'customer_id' => $customer->id,
                            ]);
                        } */
                    }
                } catch (\Throwable $th) {
                    dd($th);
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
        
            }
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
