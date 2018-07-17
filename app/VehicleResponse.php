<?php

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

    private function format(array $results): array
    {
        $formatted = array_map([$this, 'formatResult'], $results);

        usort($formatted, function ($a, $b) {
            return $a->VehicleId - $b->VehicleId;
        });

        return $formatted;
    }

    private function formatResult($vehicle): VehicleResult
    {
        return new VehicleResult($vehicle->VehicleId, $vehicle->VehicleDescription);
    }
}
