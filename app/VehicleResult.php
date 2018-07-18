<?php
/**
 * VehicleResult model
 */

namespace App;

class VehicleResult
{
    public $VehicleId;
    public $Description;
    public $CrashRating;

    public function __construct(int $id, string $desc)
    {
        $this->VehicleId  = $id;
        $this->Description = $desc;
    }
}
