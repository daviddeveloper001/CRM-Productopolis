<?php

namespace App\Services;

use App\Repositories\ShopRepository;

class ShopServices
{
    public function __construct(private ShopRepository $shopRepository){}
    public function createShop(array $data)
    {
        $shop = $this->shopRepository->findBy($data);
        return $shop ?: $this->shopRepository->create($data);
    }
}