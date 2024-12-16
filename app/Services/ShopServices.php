<?php

namespace App\Services;

use App\Repositories\ShopRepository;

class ShopServices
{
    public function __construct(private ShopRepository $shopRepository){}
    public function createShop(string $name)
    {
        $searchCriteria = ['name' => $name];

        // Preparamos el array de datos para crear
        $shopData = ['name' => $name];

        $shop = $this->shopRepository->findBy($searchCriteria);
        return $shop ?: $this->shopRepository->create($shopData);
    }
}