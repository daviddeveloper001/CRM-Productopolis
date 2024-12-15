<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Customer;
use App\Repositories\SaleRepository;

class SaleServices
{
    public function __construct(
        private SaleRepository $saleRepository,
        private CustomerServices $customerServices,
        private SegmentationServices $segmentationServices,
        private ShopServices $shopServices,
        private PaymentMethodServices $paymentMethodServices,
        private SellerServices $sellerServices,
        private ReturnAlertServices $returnAlertServices,
        private CityServices $cityServices,
        private DepartmentServices $departmentServices,
        private SegmentTypeServices $segmentTypeServices

    ) {}

    public function createSale(array $salesData)
    {
        $response = [];



        foreach ($salesData as $data) {
            try {


                $customer = Customer::where('phone', $data['telefono'])->first();

                $department = $this->departmentServices->createDepartment(['name' => $data['departamento']]);

                $city = $this->cityServices->createCity(['name' => $data['ciudad'], 'department_id' => $department->id]);

                $segmentType = $this->segmentTypeServices->createSegmentType(['name' => $data['segmentacion']]);

                $fullName = explode(' ', trim($data['nombre_cliente']));
                $firstName = $fullName[0] ?? null;
                $lastName = implode(' ', array_slice($fullName, 1));
               

                if (!$customer) {
                    $customer = $this->customerServices->createCustomer([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $data['telefono'],
                        'email' => $data['correo'],
                        'city_id' => $city->id,
                    ]);
                }


                $shop = $this->shopServices->createShop(['name' => $data['tienda']]);
                $seller = $this->sellerServices->createSeller(['name' => $data['vendedor']]);
                $paymentMethod = $this->paymentMethodServices->createPaymentMethod(['name' => $data['metodo_pago']]);
                $returnAlert = $this->returnAlertServices->createReturnAlert(['type' => $data['alerta_devolucion']]);


                $lastSale = Sale::where('customer_id', $customer->id)
                    ->orderBy('date_last_order', 'desc')
                    ->first();


                if ($lastSale && $lastSale->date_last_order >= $data['fecha_ultima_orden']) {

                    $response[] = [
                        'customer_phone' => $data['telefono'],
                        'message' => 'No se creó la venta: la fecha es menor o igual a la última venta registrada.',
                    ];
                    continue;
                }

                $this->saleRepository->create([
                    'customer_id' => $customer->id,
                    'orders_number' => $data['ordenes'],
                    'delivered' => $data['entregadas'],
                    'returns_number' => $data['devoluciones'],
                    'date_first_order' => $data['fecha_primera_orden'],
                    'date_last_order' => $data['fecha_ultima_orden'],
                    "last_order_date_delivered" => $data['fecha_ultima_orden_entregada'],
                    'total_sales' => $data['ventas'],
                    'total_revenues' => $data['ingresos'],
                    'return_value' => $data['valor_devolucion'],
                    'payment_method_id' => $paymentMethod->id,
                    'seller_id' => $seller->id,
                    'shop_id' => $shop->id,
                    'last_item_purchased' => $data['ultimo_item_comprado'],
                    'previous_last_item_purchased' => $data['antepenultimo_item_comprado'],
                    'days_since_last_purchase' => $data['ultimos_dias_compra'],
                    'return_alert_id' => $returnAlert->id,
                    'segment_type_id' => $segmentType->id,
                ]);


                $response[] = [
                    'customer_phone' => $data['telefono'],
                    'message' => 'Venta registrada con éxito.',
                ];
            } catch (\Exception $e) {
                $response[] = [
                    'customer_phone' => $data['telefono'],
                    'message' => 'Error al procesar la venta: ' . $e->getMessage(),
                ];
            }
        }

        return response()->json($response, 200);
    }
}
