<?php

namespace App\Abstracts;

use App\Models\Block;
use App\Utils\FormatUtils;
use App\Helpers\EvolutionAPI;
use App\Services\CityServices;
use App\Services\EventService;
use App\Models\SegmentRegister;
use App\Services\CountryServices;
use App\Services\CustomerServices;
use Illuminate\Support\Facades\Log;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;
use App\Interfaces\BlockActionInterface;
use App\Models\CustomerSegment;

abstract class AbstractBlockAction implements BlockActionInterface
{
    public function __construct(protected CityServices $cityServices, protected DepartmentServices $departmentServices, protected CountryServices $countryServices, protected CustomerServices $customerServices, protected EventService $eventServices) {}


    abstract protected function getApiEndpoint(): string;


    public function execute(Block $block, array $filters): void
    {

        $country = $filters['country'];
        $isLead = $filters['is_lead'];
        $exists = $filters['exists'];
        $createdSince = $filters['created_since'];
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $nextStepExecuted = $filters['next_step_executed'];

        $response = Http::get($this->getApiEndpoint(), [
            'country' => $country,
            'is_lead' => $isLead,
            'exists' => $exists,
            'created_since' => $createdSince,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'next_step_executed' => $nextStepExecuted
        ]);

        if ($response->successful()) {
            $responseData = $response->json();

            dd($responseData);

            $filteredEntryUsers = array_filter($responseData['data']['entry_clients'], function ($user) {
                return strpos($user['telefono'], "3054091063") !== false;
            });

            foreach ($filteredEntryUsers as $user) {

                $department = $this->departmentServices->createDepartment($user['departamento'] ?? 'Default Department Name');
                $city = $this->cityServices->createCity($user['ciudad'] ?? 'Default City Name', $department->id);

                $country = $this->countryServices->createCountry($user['pais'] ?? 'Colombia');
                $customer = $this->customerServices->createCustomer($user, $city->id, $country->id);

                $customer->blocks()->syncWithoutDetaching([$block->id]);

                $event = $this->eventServices->createEvent($user, $customer->id);

                $this->processBlockSpecificLogic($block, $customer, $event);
            }

            foreach ($users as $customer) {
                CustomerSegment::create([
                    'customer_id' => $customer->id,
                    'segment_id' => $block->segment->id
                ]);
            }

            Log::info("Procesamiento completado para el bloque {$block->id}");
        } else {
            Log::error("Error al conectar con la API para el bloque {$block->id}: {$response->status()}");
        }
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
