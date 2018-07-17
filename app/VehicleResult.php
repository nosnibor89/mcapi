<?php

namespace App;

class VehicleResult
{
    public $VehicleId;
    public $Description;

    public function __construct(int $id, string $desc)
    {
        $this->VehicleId  = $id;
        $this->Description = $desc;
    }
}
