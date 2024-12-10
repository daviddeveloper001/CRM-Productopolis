<?php

namespace App\Services;

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

    ) {}

    public function createSale(array $salesData)
    {
        foreach ($salesData as $data) {
            //dd($data['fecha_ultima_orden_entregada']);


            $department = $this->departmentServices->createDepartment(['name' => $data['departamento']]);
            $city = $this->cityServices->createCity(['name' => $data['ciudad'], 'department_id' => $department->id]);
            $customer = $this->customerServices->createCustomer([
                'customer_name' => $data['nombre_cliente'],
                'first_name' => $data['primer_nombre'],
                'phone' => $data['telefono'],
                'email' => $data['correo'],
                'city_id' => $city->id,
            ]);
            $shop = $this->shopServices->createShop(['name' => $data['tienda']]);
            $seller = $this->sellerServices->createSeller(['name' => $data['vendedor']]);
            $paymentMethod = $this->paymentMethodServices->createPaymentMethod(['name' => $data['metodo_pago']]);
            $segmentation = $this->segmentationServices->createSegmentation(['type' => $data['segmentacion']]);
            $returnAlert = $this->returnAlertServices->createReturnAlert(['type' => $data['alerta_devolucion']]);

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
                'segmentation_id' => $segmentation->id,
                'return_alert_id' => $returnAlert->id,
            ]);
        }
    }
}
