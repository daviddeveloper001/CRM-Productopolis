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

    
    public function findLastSaleByCustomer(int $customerId)
    {
        $lastOrders = Sale::where('customer_id', $customerId)
        ->orderBy('date_last_order', 'desc')
        ->first();


        return $lastOrders;
    }
}