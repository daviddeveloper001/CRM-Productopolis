<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Block;
use Illuminate\Console\Command;
use function Laravel\Prompts\info;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;

class ProcessBlocksCommand extends Command
{
    protected $signature = 'blocks:process'; // Nombre del comando
    protected $description = 'Procesa los bloques cuya fecha start_date coincide con el tiempo actual';

    public function handle()
    {
        $now = Carbon::now()->floorMinute(); // Ejemplo: 2024-12-16 16:23:00

        // Fecha actual + 2 minutos
        $upperLimit = $now->copy()->addMinutes(2);

        // Obtener bloques dentro del rango de tiempo actual y +2 minutos
        $blocks = Block::whereBetween('start_date', [$now, $upperLimit])->get();

    
        if ($blocks->isEmpty()) {
            log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }
    
        foreach ($blocks as $block) {

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
    

    }
}
