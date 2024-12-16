<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Sale;
use App\Services\SaleServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Block;

class SaleController extends Controller
{

    public function __construct(private SaleServices $saleServices){}

    public function index()
    {
        //
    }


    public function create()
    {
        
    }


    public function store(StoreSaleRequest $request): JsonResponse
    {
        $data = $request->validated();

        
        try {
            DB::transaction(function () use ($data) {
                $this->saleServices->createSale($data['data']);
            });

            return response()->json(['message' => 'Sales processed successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show(Sale $sale)
    {
        //
    }


    public function edit(Sale $sale)
    {
        //
    }


    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }


    public function destroy(Sale $sale)
    {
        //
    }

}
