<?php
namespace App\Actions;

use App\Models\Block;
use App\Services\CityServices;
use App\Services\EventService;
use App\Services\CustomerServices;
use Illuminate\Support\Facades\Log;
use App\Services\DepartmentServices;
use Illuminate\Support\Facades\Http;
use App\Interfaces\BlockActionInterface;

class DemonstrationAction implements BlockActionInterface
{
    public function __construct(
        private CityServices $cityServices,
        private DepartmentServices $departmentServices,
        private CustomerServices $customerServices,
        private EventService $eventServices
    ) {}

    public function execute(Block $block): void
    {
        $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_not_attend_demo');

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
                $this->eventServices->createEvent($user, $customer->id);
            }

            Log::info("Procesamiento de demostración completado para el bloque {$block->id}");
        } else {
            Log::error("Error al conectar con la API para el bloque {$block->id}: {$response->status()}");
        }
    }
}
