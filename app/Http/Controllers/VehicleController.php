<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VehicleService;
use App\VehicleResponse;

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

    public function fetch(Request $request, string $modelYear = null, string $manufacturer = null, string $model = null)
    {
        if ($request->isMethod('post')) {
            $modelYear = $request->input('modelYear');
            $manufacturer = $request->manufacturer;
            $model = $request->model;
        }

        try {
            // Validate input
            if ($this->validateInput($modelYear, $manufacturer, $model)) {
                $results = $this->vehicleService->fetch((int)$modelYear, $manufacturer, $model);
            } else {
                $results = new VehicleResponse(0, []);
            }
        } catch (Exception $e) {
            $results = new VehicleResponse(0, []);
        }

        return response()->json($results);
    }

    // Maybe should go into a validator...
    private function validateInput(string $modelYear, string $manufacturer, string $model)
    {
        if ((empty($modelYear) || !is_numeric($modelYear))) {
            return false;
        }

        if ((empty($manufacturer) || $manufacturer === 'undefined')) {
            return false;
        }

        if ((empty($model) || $model === 'undefined')) {
            return false;
        }

        return true;
    }
}
