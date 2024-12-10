<?php

namespace App\Services;

use App\Repositories\ReturnAlertRepository;

class ReturnAlertServices
{
    public function __construct(private ReturnAlertRepository $returnAlertRepository){}
    public function createReturnAlert(array $data)
    {
        $returnAlert = $this->returnAlertRepository->findBy($data);
        return $returnAlert ?: $this->returnAlertRepository->create($data);
    }
}