<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Seller;
use App\Models\Shop;

class CrmController extends Controller
{
    public function index()
    {
        dd('index');
    }


    public function store(Request $request)
    {
        $datos = $request->all();



        DB::transaction(function () use ($datos) {

            foreach ($datos['data'] as $registro) {
                // Buscar o crear método de pago
                $metodoPago = PaymentMethod::firstOrCreate(['method' => $registro['metodo_pago']]);

                // Buscar o crear cliente
                $tienda = Shop::firstOrCreate(
                    ['name' => $registro['tienda']]
                );


                // Buscar o crear vendedor
                $vendedor = Seller::firstOrCreate([
                    'name' => $registro['vendedor'],
                    'last_name' => $registro['vendedor'],
                    'shop_id' => $tienda->id,
                ]);
            }
        });
    }


    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
