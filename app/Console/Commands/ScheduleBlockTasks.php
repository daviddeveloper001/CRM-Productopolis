<?php

namespace App\Console\Commands;

use App\Models\Block;
use Illuminate\Console\Command;
use App\Factory\BlockActionFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

class ScheduleBlockTasks extends Command
{
    protected $signature = 'blocks:schedule'; // Nombre del comando
    protected $description = 'Programa las tareas de bloques en función de su fecha de inicio (start_date)';

    /**
     * Ejecuta la programación de tareas.
     */
    public function handle(): void
    {
        $this->info('Iniciando la programación de tareas de bloques...');

        $blocks = Block::whereNotNull('start_date')->get();

        foreach ($blocks as $block) {
            $startDate = $block->start_date;

            // Programar tarea dinámicamente en el programador de Laravel
            Schedule::call(function () use ($block) {
                $action = BlockActionFactory::getAction($block->exit_criterion);

                if ($action) {
                    $action->execute($block);
                    Log::info("Acción ejecutada para el bloque programado: {$block->id}");
                } else {
                    Log::warning("No se encontró acción para el bloque programado: {$block->id}");
                }

                // Lógica de envío de mensajes (puedes implementarlo más adelante)
                $this->sendMessage($block);

            })->at($startDate->format('H:i')); // Ejecutar a la hora especificada

            $this->info("Tarea programada para el bloque ID {$block->id} a las {$startDate}");
        }

        $this->info('Programación de tareas completada.');
    }

    /**
     * Lógica de envío de mensajes.
     */
    private function sendMessage(Block $block): void
    {
        Log::info("Enviando mensaje relacionado con el bloque {$block->id}");
        // Aquí puedes invocar tu servicio de mensajería
        // Ejemplo: $this->messageService->sendBlockNotification($block);
    }
}
