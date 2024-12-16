<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodServices
{
    public function __construct(private PaymentMethodRepository $paymentMethodRepository){}
    public function createPaymentMethod(string $name)
    {

        $searchCriteria = ['name' => $name];

        // Preparamos el array de datos para crear
        $paymentMethodData = ['name' => $name];

        $paymentMethod = $this->paymentMethodRepository->findBy($searchCriteria);
        return $paymentMethod ?: $this->paymentMethodRepository->create($paymentMethodData);
    }
}