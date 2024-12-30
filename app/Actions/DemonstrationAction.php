<?php

namespace App\Actions;

use App\Abstracts\AbstractBlockAction;



class DemonstrationAction extends AbstractBlockAction
{

    protected function getApiEndpoint(): string
    {
        return 'https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_scheduling_and_demo';
    }
}
