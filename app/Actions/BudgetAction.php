<?php
namespace App\Actions;


use App\Models\Block;
use Illuminate\Support\Facades\Log;
use App\Interfaces\BlockActionInterface;

class BudgetAction implements BlockActionInterface
{
    public function execute(Block $block): void
    {
        // Lógica específica para Agendamiento
        Log::info('Procesando Agendamiento para el bloque', ['block_id' => $block->id]);
        // Agrega aquí la acción concreta
    }
}
