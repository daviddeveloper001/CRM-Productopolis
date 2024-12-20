<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
        private SegmentTypeServices $segmentTypeServices,
        private CountryServices $countryServices

    ) {}

    public function createSale(array $salesData): JsonResponse
    {
        $responses = [];

        foreach ($salesData as $data) {
            $response = $this->processSaleData($data);
            $responses[] = $response;
        }

        return response()->json($responses, 200);
    }

    private function processSaleData(array $data): array
    {
        try {

            $department = $this->departmentServices->createDepartment($data['departamento']?? 'Default City Name');

            $city = $this->cityServices->createCity($data['ciudad'] ?? 'Default City Name', $department->id);
            $segmentType = $this->segmentTypeServices->createSegmentType($data['segmentacion']);
            $shop = $this->shopServices->createShop($data['tienda']);
            $seller = $this->sellerServices->createSeller($data['vendedor']);
            $paymentMethod = $this->paymentMethodServices->createPaymentMethod($data['metodo_pago']);
            $returnAlert = $this->returnAlertServices->createReturnAlert($data['alerta_devolucion']);
            $country = $this->countryServices->createCountry($data['pais'] ?? 'Colombia');
            
            $customer = $this->customerServices->createCustomer($data, $city->id, $country->id);

            $lastSale = $this->saleRepository->findLastSaleByCustomer($customer->id);

            if ($lastSale && $lastSale->date_last_order >= $data['fecha_ultima_orden']) {
                return [
                    'customer_phone' => $data['telefono'],
                    'message' => 'No se creó la venta: la fecha es menor o igual a la última venta registrada.',
                ];
            }

            $sale = $this->saleRepository->create([
                'customer_id' => $customer->id,
                'orders_number' => $data['ordenes'],
                'delivered' => $data['entregadas'],
                'returns_number' => $data['devoluciones'],
                'date_first_order' => $data['fecha_primera_orden'],
                'date_last_order' => $data['fecha_ultima_orden'],
                'last_order_date_delivered' => $data['fecha_ultima_orden_entregada'],
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

            return [
                'customer_phone' => $data['telefono'],
                'message' => 'Venta registrada con éxito.',
                'sale_id' => $sale->id, 
            ];

        } catch (\Exception $e) {
            Log::error("Error al procesar la venta: " . $e->getMessage() . " Data: " . json_encode($data)); 
            return [
                'customer_phone' => $data['telefono'] ?? null, 
                'message' => 'Error al procesar la venta: ' . $e->getMessage(),
            ];
        }
    }


}
