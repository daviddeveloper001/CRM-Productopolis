<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Block;
use App\Enum\EventEnum;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\CityServices;
use App\Services\EventService;
use App\Services\CustomerServices;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;




class BlockController extends Controller
{
    /* public function __construct(private CityServices $cityServices, private DepartmentServices $departmentServices, private CustomerServices $customerServices, private EventService $eventServices) {} */
    public function index()
    {
        /* $blocks = Block::all();

        foreach ($blocks as $key => $value) {

            if ($value->exit_criterion == EventEnum::Agendamiento->getLabel()) 
            {
                $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_scheduling');

                if ($response->successful()) {

                    $users = $response->json();

                    foreach ($users['data'] as $user) {
                        
                        if (empty($user['ciudad']) || empty($user['departamento']) || empty($user['telefono']) || empty($user['correo'])) {
                            continue;
                        }
                    
                        $department = $this->departmentServices->createDepartment($user['departamento']);

                        $city = $this->cityServices->createCity($user['ciudad'], $department->id);
                    
                        $customer = $this->customerServices->createCustomer($user, $city->id);

                        $customer->blocks()->syncWithoutDetaching([$value->id]);

                        $event = $this->eventServices->createEvent($user, $customer->id);
                    }
                    

                    return response()->json($users);
                }

                return response()->json([
                    'error' => 'No se logró conectar a la API'
                ], $response->status());
            }

            if ($value->exit_criterion == EventEnum::Demostracion->getLabel()) 
            {
                $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_not_attend_demo');

                if ($response->successful()) {

                    $users = $response->json();

                    foreach ($users['data'] as $user) {
                        
                        if (empty($user['ciudad']) || empty($user['departamento']) || empty($user['telefono']) || empty($user['correo'])) {
                            continue;
                        }
                    
                        $department = $this->departmentServices->createDepartment($user['departamento']);

                        $city = $this->cityServices->createCity($user['ciudad'], $department->id);
                    
                        $customer = $this->customerServices->createCustomer($user, $city->id);

                        $customer->blocks()->syncWithoutDetaching([$value->id]);

                        $event = $this->eventServices->createEvent($user, $customer->id);
                    }
                    

                    return response()->json($users);
                }

                return response()->json([
                    'error' => 'No se logró conectar a la API'
                ], $response->status());
            }
        } */

        // Fecha actual redondeada al minuto
        $now = Carbon::now()->floorMinute(); // Ejemplo: 2024-12-16 16:23:00

        // Fecha actual + 2 minutos
        $upperLimit = $now->copy()->addMinutes(2);

        // Obtener bloques dentro del rango de tiempo actual y +2 minutos
        $blocks = Block::whereBetween('start_date', [$now, $upperLimit])->get();

        dd('Block consultados' . $blocks);

        if ($blocks->isEmpty()) {

            dd('no hay datos');
            Log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }


        foreach ($blocks as $block) {
            Log::info("Procesando el bloque ID: {$block->id}");

            // Obtener la acción correspondiente al exit_criterion del bloque
            $action = BlockActionFactory::getAction($block->exit_criterion);

            if ($action) {
                try {
                    $action->execute($block);
                    Log::info("Acción ejecutada para el bloque: {$block->id}");
                } catch (\Exception $e) {
                    Log::error("Error al ejecutar la acción para el bloque {$block->id}: {$e->getMessage()}");
                }
            } else {
                Log::warning("No se encontró acción para el criterio: {$block->exit_criterion}");
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
