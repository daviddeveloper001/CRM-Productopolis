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
            Log::info("Procesando bloque ID: {$block->id}, Criterio: {$block->exit_criterion}");
        
            $action = BlockActionFactory::getAction($block->exit_criterion);
        
            if ($action) {
                try {
                    Log::info("Acción creada exitosamente: " . get_class($action));
                    
                    $filters = [
                        'country' => $block->campaign->filters['country'],
                        'type_user' => $block->campaign->filters['user_type'],
                        'event' => $block->campaign->filters['event'],
                        'confirmation' => $block->campaign->filters['confirmation'] ? '1' : '0'
                    ];
                    
                    Log::info("Ejecutando acción con filtros: " . json_encode($filters));
        
                    $action->execute($block, $filters);
        
                    Log::info("Acción ejecutada para el bloque: {$block->id}");
                } catch (\Exception $e) {
                    Log::error("Error al ejecutar la acción para el bloque {$block->id}: {$e->getMessage()}");
                    Log::error("Stack trace: " . $e->getTraceAsString());
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
