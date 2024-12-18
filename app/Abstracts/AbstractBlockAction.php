<?php

namespace App\Abstracts;

use App\Models\Block;
use App\Utils\FormatUtils;
use App\Helpers\EvolutionAPI;
use App\Services\CityServices;
use App\Services\EventService;
use App\Services\CustomerServices;
use Illuminate\Support\Facades\Log;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;
use App\Interfaces\BlockActionInterface;

abstract class AbstractBlockAction implements BlockActionInterface
{
    public function __construct(protected CityServices $cityServices, protected DepartmentServices $departmentServices, protected CustomerServices $customerServices, protected EventService $eventServices){}


    abstract protected function getApiEndpoint(): string;


    public function execute(Block $block, array $filters): void
    {


        $country = $filters['country'] ?? null;
        $typeUser = $filters['type_user'] ?? null;
        $event = $filters['event'] ?? null;
        $confirmation = $filters['confirmation'] ? '1' : '0';
        
        $response = Http::get($this->getApiEndpoint(), [
            'country' => $country,
            'type_user' => $typeUser,
            'event' => $event,
            'confirmation' => $confirmation
        ]);


        if ($response->successful()) {
            $users = $response->json();
            foreach ($users['data'] as $user) {

                $department = $this->departmentServices->createDepartment($user['departamento']);
                $city = $this->cityServices->createCity($user['ciudad'], $department->id);
                $customer = $this->customerServices->createCustomer($user, $city->id);
                $customer->blocks()->syncWithoutDetaching([$block->id]);


                $event = $this->eventServices->createEvent($user, $customer->id);


                $this->processBlockSpecificLogic($block, $customer, $event);
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
                'message' => FormatUtils::replaceSchedulingPlaceholders(
                    $block->template->content,
                    $customer->id,
                    $event->id
                ),
                'filename' => "",
                'attachment_url' => "",
            ];
            EvolutionAPI::send_from_data($dataToSend);
        }
    }
}
