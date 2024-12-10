<?php

namespace App\Services;

use App\Repositories\SellerRepository;

class SellerServices
{
    public function __construct(private SellerRepository $sellerRepository){}
    public function createSeller(array $data)
    {
        $seller = $this->sellerRepository->findBy($data);
        return $seller?: $this->sellerRepository->create($data);
    }
}