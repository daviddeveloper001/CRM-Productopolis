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
    protected $signature = 'blocks:process'; 
    protected $description = 'Procesa los bloques cuya fecha start_date coincide con el tiempo actual';

    public function handle()
    {
        $now = Carbon::now()->floorMinute(); 

        $upperLimit = $now->copy()->addMinutes(2);

        $blocks = Block::whereBetween('start_date', [$now, $upperLimit])->get();

    
        if ($blocks->isEmpty()) {
            log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }
    
        foreach ($blocks as $block) {

            $action = BlockActionFactory::getAction($block->exit_criterion);
    
            if ($action) {
                try {
                    $action->execute($block, [
                        'country' => $block->campaign->filters['country'],
                        'type_user' => $block->campaing->filters['user_type'],
                        'event' => $block->campaing->filters['event'],
                        'confirmation' => $block->campaign->filters['confirmation'] ? '1' : '0'
                    ]);
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
