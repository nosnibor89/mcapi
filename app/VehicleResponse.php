<?php

/**
 * VehicleResponse model
 */

namespace App;

use App\VehicleResult;

class VehicleResponse
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
        $formatted = array_map([$this, 'formatResult'], $results);

        usort($formatted, function ($a, $b) {
            return $a->VehicleId - $b->VehicleId;
        });

        return $formatted;
    }

    /**
     * Formats each vehicle data
     *
     * @param object $vehicle Vehicles object comming from API
     * @return VehicleResult
     */
    private function formatResult(object $vehicle): VehicleResult
    {
        $desc = property_exists($vehicle, 'VehicleDescription') ? $vehicle->VehicleDescription : $vehicle->Description;

        $currentVehicle = new VehicleResult($vehicle->VehicleId, $desc);

        if (property_exists($vehicle, 'CrashRating')) {
            $currentVehicle->CrashRating = $vehicle->CrashRating;
        } else {
            unset($currentVehicle->{'CrashRating'});
        }

        return $currentVehicle;
    }
}
