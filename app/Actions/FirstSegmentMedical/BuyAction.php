<?php

namespace App\Actions\FirstSegmentMedical;

use App\Abstracts\AbstractCampaingAction;

class BuyAction extends AbstractCampaingAction
{
    protected function getApiEndpoint(): string
    {

        return 'https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_estimate_and_purchase';
    }
}
