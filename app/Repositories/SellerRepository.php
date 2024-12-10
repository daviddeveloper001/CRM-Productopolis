<?php

namespace App\Repositories;

use App\Models\Seller;

class SellerRepository extends BaseRepository 
{
    const RELATIONS = ['sales'];

    public function __construct(Seller $seller)
    {
        parent::__construct($seller, self::RELATIONS);
    }
}