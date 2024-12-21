<?php

namespace App\Actions;

use App\Abstracts\AbstractBlockAction;


class SchedulingAction extends AbstractBlockAction
{
    /* public function __construct(private CityServices $cityServices, private DepartmentServices $departmentServices, private CustomerServices $customerServices, private EventService $eventServices) {}
    public function execute(Block $block, array $filters): void
    {
        $country = $filters['country'] ?? null;
        $typeUser = $filters['type_user'] ?? null;

        $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_scheduling', [
            'country' => $country,
            'type_user' => $typeUser,
        ]);

        if ($response->successful()) {
            $users = $response->json();

            foreach ($users['data'] as $user) {
                if (empty($user['ciudad']) || empty($user['departamento']) || empty($user['telefono']) || empty($user['correo'])) {
                    continue;
                }

                $department = $this->departmentServices->createDepartment($user['departamento']);


                $city = $this->cityServices->createCity($user['ciudad'], $department->id);

                $customer = $this->customerServices->createCustomer($user, $city->id);


                $customer->blocks()->syncWithoutDetaching([$block->id]);

                $event = $this->eventServices->createEvent($user, $customer->id);

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

            Log::info("Procesamiento de Agendamiento completado para el bloque {$block->id}");
        } else {
            Log::error("Error al conectar con la API para el bloque {$block->id}: {$response->status()}");
        }
    } */

    protected function getApiEndpoint(): string
    {
        return 'https://app.monaros.co/sistema/index.php/public_routes/get_clients_by_scheduling_and_demo';
    }
}
