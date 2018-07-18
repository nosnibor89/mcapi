<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VehicleService;
use App\ApiResponse;

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

    /**
     * Fetch vehicles data
     *
     * @param Request $request Lumen Request object
     * @param string $modelYear Year of the vehicles to find
     * @param string $manufacturer  manufacture or make of vehicles to find
     * @param string $model model of vehicles
     * @return void
     */
    public function fetch(Request $request, string $modelYear = null, string $manufacturer = null, string $model = null)
    {
        if ($request->isMethod('post')) {
            $modelYear = $request->input('modelYear');
            $manufacturer = $request->input('manufacturer');
            $model = $request->input('model');
        }

        $withRating = $request->query('withRating') === 'true' ? true: false;

        try {
            // Validate input
            if ($this->validateInput($modelYear, $manufacturer, $model)) {
                $results = $this->vehicleService->fetch((int)$modelYear, $manufacturer, $model, $withRating);
            } else {
                $results = new ApiResponse(0, []);
            }
        } catch (Exception $e) {
            $results = new ApiResponse(0, []);
        }

        return response()->json($results);
    }

    // TODO: Maybe should go into a validator...
    /**
     * Validates input for /vehicles endpoint
     *
     * @param string $modelYear Year of the vehicles to find
     * @param string $manufacturer  manufacture or make of vehicles to find
     * @param string $model model of vehicles
     * @return void
     */
    private function validateInput(string $modelYear = null, string $manufacturer = null, string $model = null)
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
