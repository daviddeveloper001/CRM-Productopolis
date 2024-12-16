<?php

namespace App\Http\Controllers\Api\V1;

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


        $blocks = Block::all();

        foreach ($blocks as $block) {
            $action = BlockActionFactory::getAction($block->exit_criterion);

            if ($action) {
                $action->execute($block);
            } else {
                Log::warning("No se encontró acción para el criterio: {$block->exit_criterion}");
            }
        }

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
