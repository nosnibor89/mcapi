<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VehicleService;

class VehicleController extends Controller
{

    private $vehicleService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehicleService $vs)
    {
        $this->vehicleService = $vs;
    }

    public function fetch(Request $request)
    {
        // print_r($request);
        print_r($this->vehicleService);
    }
}
