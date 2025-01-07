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
use App\Interfaces\CampaignFirstSegmentMedicalInterface;
use App\Models\Campaign;
use App\Models\CustomerSegment;

abstract class AbstractCampaingAction implements CampaignFirstSegmentMedicalInterface
{
    public function __construct(protected CityServices $cityServices, protected DepartmentServices $departmentServices, protected CountryServices $countryServices, protected CustomerServices $customerServices, protected EventService $eventServices) {}


    abstract protected function getApiEndpoint(): string;


    public function executeFirtstSegmentMedical(Campaign $campaign, array $filters): void
    {

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
            $entryClients = $responseData['data']['entry_clients'];

            //dd($entryClients);

        
            $customerIds = []; // IDs de clientes para sincronizar con la campaña
        

            foreach ($entryClients as $user) {

                // Crear el departamento
                $department = $this->departmentServices->createDepartment($user['departamento'] ?? 'Default Department Name');

                
                // Crear la ciudad asociada al departamento
                $city = $this->cityServices->createCity($user['ciudad'] ?? 'Default City Name', $department->id);
                
                // Crear el país
                $country = $this->countryServices->createCountry($user['pais'] ?? 'Colombia');
                
                // Crear el cliente y obtener su ID
                $customer = $this->customerServices->createCustomer($user, $city->id, $country->id);
                
                // Agregar el ID del cliente a la lista para sincronizar con la campaña
                $customerIds[] = $customer->id;
        
                // Crear el evento relacionado con el cliente
                //$this->eventServices->createEvent($user, $customer->id);
            }
            // IDs de clientes para sincronizar con la campaña
        
            // Sincronizar los IDs de los clientes creados con la campaña
            $campaign->customers()->sync($customerIds);
        }
        
    }
}
