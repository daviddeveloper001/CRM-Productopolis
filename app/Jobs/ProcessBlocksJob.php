<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Block;
use App\Models\Segment;
use Illuminate\Support\Facades\Log;
use App\Factory\CampaignActionFactory;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessBlocksJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now()->floorMinute();

        $upperLimit = $now->copy()->addMinutes(2);

        $blocks = Block::whereBetween('start_date', [$now, $upperLimit])->get();


        if ($blocks->isEmpty()) {
            log::info('No hay bloques programados para ejecutarse en este momento.');
            return;
        }

        foreach ($blocks as $block) {


            $campaign = $block->campaign;

            $typeCampaign = CampaignActionFactory::getAction($campaign->type_campaign);


            if ($typeCampaign) {
                try {
                    $typeCampaign->executeCampaign($block);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        }
    }
}
