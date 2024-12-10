<?php

namespace App\Repositories;

use App\Models\Shop;

class ShopRepository extends BaseRepository 
{
    const RELATIONS = ['sales'];

    public function __construct(Shop $shop)
    {
        parent::__construct($shop, self::RELATIONS);
    }
}