<?php

namespace App;

class VehicleResponse
{
    public $Count = 0;
    public $Results = [];

    public function __construct(int $count = 0, array $results = [])
    {
        $this->Count  = $count;
        $this->Results = $results;
    }
}
