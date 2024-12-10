<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository extends BaseRepository 
{
    const RELATIONS = ['customer', 'shop', 'seller', 'segmentation', 'payment_method', 'returnAlert', 'segmentRegisters'];

    public function __construct(Sale $sale)
    {
        parent::__construct($sale, self::RELATIONS);
    }
}