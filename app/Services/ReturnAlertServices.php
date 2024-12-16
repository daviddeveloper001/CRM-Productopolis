<?php

namespace App\Services;

use App\Repositories\ReturnAlertRepository;

class ReturnAlertServices
{
    public function __construct(private ReturnAlertRepository $returnAlertRepository){}
    public function createReturnAlert(string $name)
    {

        $searchCriteria = ['type' => $name];

        $returnAlertdData = ['type' => $name];

        $returnAlert = $this->returnAlertRepository->findBy($searchCriteria);
        return $returnAlert ?: $this->returnAlertRepository->create($returnAlertdData);
    }
}