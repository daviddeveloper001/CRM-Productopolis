<?php

namespace App\Console\Commands;

use App\Models\Block;
use Illuminate\Console\Command;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\info;

class ProcessBlocksCommand extends Command
{
    protected $signature = 'blocks:process'; // Nombre del comando
    protected $description = 'Procesa los bloques y ejecuta sus acciones cada 5 minutos';
    public function handle()
    {
        $this->info('Procesando bloques...');

        $blocks = Block::all();

        foreach ($blocks as $block) {
            $action = BlockActionFactory::getAction($block->exit_criterion);

            if ($action) {
                $action->execute($block);
                $this->info("Acción ejecutada para el bloque: {$block->id}");
            } else {
                Log::warning("No se encontró acción para el criterio: {$block->exit_criterion}");
                $this->warn("No se encontró acción para el criterio: {$block->exit_criterion}");
            }
        }

        info('Procesamiento de bloques completado.');

        $this->info('Procesamiento de bloques completado.');
    }
}
