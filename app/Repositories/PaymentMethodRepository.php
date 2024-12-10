<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository extends BaseRepository 
{
    const RELATIONS = ['sales'];

    public function __construct(PaymentMethod $paymentMethod)
    {
        parent::__construct($paymentMethod, self::RELATIONS);
    }
}