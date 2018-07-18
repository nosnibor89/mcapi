<?php

/**
 * ApiResponse model
 */

namespace App;

use App\Vehicle;

class ApiResponse
{
    public $Count = 0;
    public $Results = [];

    public function __construct(int $count = 0, array $results = [])
    {
        $this->Count  = $count;
        $this->Results = $this->format($results);
    }

    /**
     * Formats the "Results" array
     *
     * @param array $results    Vehicles results
     * @return array
     */
    private function format(array $results): array
    {
        $formatted = array_map([$this, 'formatVehicle'], $results);

        usort($formatted, function ($a, $b) {
            return $a->VehicleId - $b->VehicleId;
        });

        return $formatted;
    }

    /**
     * Formats each vehicle data
     *
     * @param object $vehicle Vehicles object comming from API
     * @return Vehicle
     */
    private function formatVehicle(object $vehicle): Vehicle
    {
        $desc = property_exists($vehicle, 'VehicleDescription') ? $vehicle->VehicleDescription : $vehicle->Description;

        $currentVehicle = new Vehicle($vehicle->VehicleId, $desc);

        if (property_exists($vehicle, 'CrashRating')) {
            $currentVehicle->CrashRating = $vehicle->CrashRating;
        } else {
            unset($currentVehicle->{'CrashRating'});
        }

        return $currentVehicle;
    }
}
