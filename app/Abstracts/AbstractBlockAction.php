<?php

namespace App\Abstracts;

use App\Models\Block;
use App\Models\Campaign;
use App\Utils\FormatUtils;
use App\Helpers\EvolutionAPI;
use App\Services\CityServices;
use App\Services\EventService;
use App\Models\CustomerSegment;
use App\Models\SegmentRegister;
use App\Services\CountryServices;
use App\Services\SegmentServices;
use App\Services\CustomerServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;
use App\Interfaces\BlockActionInterface;

abstract class AbstractBlockAction implements BlockActionInterface
{
    public function __construct(protected CityServices $cityServices, protected DepartmentServices $departmentServices,  protected CountryServices $countryServices, protected CustomerServices $customerServices, protected EventService $eventServices, private SegmentServices $segmentServices,) {}


    abstract protected function getApiEndpoint(): string;


    public function execute(Block $block, array $filters): void
    {

        $segment = $this->segmentServices->createSegment($block->id);

        $campaign = $block->campaign;

        $country = $filters['country'];
        $isLead = $filters['is_lead'];
        $exists = $filters['exists'];
        $createdSince = $filters['created_since'];
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $nextStepExecuted = $filters['next_step_executed'];

        try {
            $response = Http::get($this->getApiEndpoint(), [
                'country' => $country,
                'is_lead' => $isLead,
                'exists' => $exists,
                'created_since' => $createdSince,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'next_step_executed' => $nextStepExecuted
            ]);
        } catch (\Exception $e) {
            log::error('Error HTTP:', $e->getMessage());
        }

        if ($response->successful()) {

            $responseData = $response->json();

            // Obtener únicamente los clientes de "entry_clients"
            $entryClients = collect($responseData['data']['entry_clients']); // Convertir a colección

            $exitclients = $responseData['data']['exit_clients']; // Convertir a colección de exit_clients

            // Obtener los teléfonos de los clientes ya asociados a la campaña
            $existingPhones = DB::table('campaign_customers')
                ->join('customers', 'campaign_customers.customer_id', '=', 'customers.id')
                ->where('campaign_customers.campaign_id', $campaign->id)
                ->pluck('customers.phone');


            // Filtrar los clientes nuevos (que no están en la campaña)
            $newCustomers = $entryClients->filter(function ($client) use ($existingPhones) {
                return !$existingPhones->contains($client['telefono']);
            });


            // Sincronizar los nuevos clientes con la campaña
            $campaign->customers()->syncWithoutDetaching(
                $newCustomers->pluck('id')->toArray()
            );


            $filteredEntryUsers = array_filter($responseData['data']['entry_clients'], function ($user) {
                return strpos($user['telefono'], "3054091063") !== false;
            });


            foreach ($filteredEntryUsers as $user) {

                $department = $this->departmentServices->createDepartment($user['departamento'] ?? 'Default Department Name');

                $city = $this->cityServices->createCity($user['ciudad'] ?? 'Default City Name', $department->id);

                $country = $this->countryServices->createCountry($user['pais'] ?? 'Colombia');

                $customer = $this->customerServices->createCustomer($user, $city->id, $country->id);

                /* $customer->blocks()->syncWithoutDetaching([$block->id]);

                $event = $this->eventServices->createEvent($user, $customer->id);

                $this->processBlockSpecificLogic($block, $customer, $event); */

                //$customerIds[] = $customer->id;
            }

            $this->notifyCustomersWithoutRecentPurchase($campaign, $block, $exitclients);

            /* $customerIds = [];

            $segment->customers()->syncWithoutDetaching($customerIds); */

            Log::info("Procesamiento completado para el bloque {$block->id}");
        } else {
            Log::error("Error al conectar con la API para el bloque {$block->id}: {$response->status()}");
        }
    }


    public function notifyCustomersWithoutRecentPurchase(Campaign $campaign, Block $currentBlock, $exitclients)
    {

        $customersWithoutRecentPurchase = $this->findCustomersWithoutRecentPurchase($campaign, $currentBlock, $exitclients);

        foreach ($customersWithoutRecentPurchase as $customer) {

            $this->sendNotification($customer);
        }
    }


    public function findCustomersWithoutRecentPurchase(Campaign $campaign, Block $currentBlock, $exitclients)
    {
        // Obtener los números de teléfono de los clientes con fulfillment_status != 'fulfilled'
        $customers = DB::table('campaign_customers')
            ->join('customers', 'campaign_customers.customer_id', '=', 'customers.id')
            ->where('campaign_customers.campaign_id', $campaign->id)
            ->where('campaign_customers.fulfillment_status', '!=', 'fulfilled')
            ->get([
                'campaign_customers.customer_id',
                'campaign_customers.last_purchase_at',
                'campaign_customers.fulfilled_via_block_id',
                'campaign_customers.fulfillment_status',
                'customers.phone',
            ]);

        // Obtener el bloque anterior al bloque actual
        $previousBlock = $campaign->blocks()
            ->where('created_at', '<', $currentBlock->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        // Filtrar los clientes basándonos en los teléfonos que aparecen en $exitclients
        $exitPhones = collect($exitclients)->pluck('phone')->toArray();

        $customersWithoutRecentPurchase = $customers->map(function ($customer) use ($previousBlock, $exitPhones) {
            // Excluir clientes que están en $exitclients
            if (in_array($customer->phone, $exitPhones)) {
                // Marcar como fulfilled
                DB::table('campaign_customers')
                    ->where('customer_id', $customer->customer_id)
                    ->update([
                        'fulfillment_status' => 'fulfilled',
                        'fulfilled_via_block_id' => $previousBlock ? $previousBlock->id : null,
                    ]);

                return null; // Excluir del resultado
            }

            // Retornar clientes que no están en $exitclients
            return [
                'customer_id' => $customer->customer_id,
                'last_purchase_at' => $customer->last_purchase_at,
                'fulfillment_status' => $customer->fulfillment_status,
                'fulfilled_via_block_id' => $customer->fulfilled_via_block_id,
                'phone' => $customer->phone,
            ];
        })->filter();

        // Crear registros en CustomerSegment para los clientes que aún no han cumplido
        foreach ($customersWithoutRecentPurchase as $customer) {
            CustomerSegment::create([
                'customer_id' => $customer['customer_id'],
                'segment_id' => $currentBlock->segment->id,
            ]);
        }

        return $customersWithoutRecentPurchase;
    }


    protected function sendNotification($customer)
    {


        dd('enviado mensaje ');
    }


    protected function processBlockSpecificLogic(Block $block, $customer, $event)
    {
        if ($block->template->type === 'whatsapp') {
            $dataToSend = [
                'phone' => $customer->phone,
                'message' => FormatUtils::replaceCustomerPlaceholders(
                    $block->template->content,
                    $customer->id,
                    $event->id
                ),
                'filename' => "test.jpg",
                'attachment_url' => "",
            ];
            EvolutionAPI::send_from_data($dataToSend);
        }

        $listId = $block->template->whatsapp_list_id;

        if ($listId) {
            EvolutionAPI::send_whatsapp_list_EA($listId, $customer->phone);
        }
    }
}
