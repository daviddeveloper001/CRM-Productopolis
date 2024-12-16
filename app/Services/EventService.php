<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;

class EventService
{
    public function __construct(private EventRepository $eventRepository) {}

    public function createEvent(array $eventData, int $customerId)
    {

        return $this->eventRepository->create([
            'customer_id' => $customerId,
            'event_start' => $eventData['event_start_date'] . ' ' . $eventData['event_start_time'],
            'event_end' => $eventData['event_end_date'] . ' ' . $eventData['event_end_time'],
            'event_title' => $eventData['event_title'],
            'event_description' => $eventData['event_description']
        ]);
    }
}
