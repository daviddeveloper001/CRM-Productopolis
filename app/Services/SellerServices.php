<?php

namespace App\Services;

use App\Repositories\SellerRepository;

class SellerServices
{
    public function __construct(private SellerRepository $sellerRepository){}
    public function createSeller(string $name)
    {

        $searchCriteria = ['name' => $name];

        // Preparamos el array de datos para crear
        $sellerData = ['name' => $name];

        $seller = $this->sellerRepository->findBy($searchCriteria);
        return $seller ?: $this->sellerRepository->create($sellerData);
    }
}