<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessConsultationMedical implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Campaign $campaign)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $country = $this->campaign->filters['country'];
        $userType = $this->campaign->filters['user_type'];
        $event = $this->campaign->filters['event'];
        $confirmation = $this->campaign->filters['confirmation'] ? '1' : '0';
        $event2 = $this->campaign->filters['event2'];
        $confirmation2 = $this->campaign->filters['confirmation2'] ? '1' : '0';
        
        
        try {
            $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_not_attend_demo'/* , [
                'country' => $country,
                'user_type' => $userType,
                'event' => $event,
                'confirmation' => $confirmation,
                'event2' => $event2,
                'confirmation2' => $confirmation2
            ] */);

            //dd($response->json());
            
            if ($response->successful()) {
                $data = $response->json();
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
