<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\City;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\ReturnAlert;
use App\Models\Segmentation;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

                $department = Department::firstOrCreate(
                    ['name' => $registro['departamento']]
                );

                $city = City::firstOrCreate(
                    [
                        'name' => $registro['ciudad'],
                        'department_id' => $department->id
                    ]
                );

                $customer = Customer::firstOrCreate(
                    [
                        'customer_name' => $registro['nombre_cliente'],
                        'first_name' => $registro['primer_nombre'],
                        'phone' => $registro['telefono'],
                        'email' => $registro['correo'],
                        'city_id' => $city->id
                    ]
                );

                $shop = Shop::firstOrCreate(
                    ['name' => $registro['tienda']]
                );

                $seller = Seller::firstOrCreate(
                    [
                        'name' => $registro['vendedor'],
                    ]
                );

                $paymentMethod = PaymentMethod::firstOrCreate(['name' => $registro['metodo_pago']]);

                $segmentation = Segmentation::firstOrCreate(
                    ['type' => $registro['segmentacion']]
                );

                $returnAlert = ReturnAlert::firstOrCreate(
                    ['type' => $registro['alerta_devolucion']]
                );

                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'shop_id' => $shop->id,
                    'seller_id' => $seller->id,
                    'payment_method_id' => $paymentMethod->id,
                    'segmentation_id' => $segmentation->id,
                    'return_alert_id' => $returnAlert->id,
                    "order_date" => $registro['fecha_primera_orden'],
                    "last_order_date_delivered" => $registro['fecha_ultima_orden'],
                    "total_sales" => $registro['ventas'],
                    "total_revenues" => $registro['ingresos'],
                    "orders_number" => $registro['ordenes'],
                    "number_entries" => $registro['entregadas'],
                    "returns_number" => $registro['devoluciones'],
                    "return_value" => $registro['valor_devolucion'],
                    "last_days_purchase_days" => $registro['dias_ultima_compra'],
                    "last_item_purchased" => $registro['ultimo_item_comprado'], 
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
