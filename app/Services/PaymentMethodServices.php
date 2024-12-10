<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodServices
{
    public function __construct(private PaymentMethodRepository $paymentMethodRepository){}
    public function createPaymentMethod(array $data)
    {
        $paymentMethod = $this->paymentMethodRepository->findBy($data);
        return $paymentMethod ?: $this->paymentMethodRepository->create($data);
    }
}