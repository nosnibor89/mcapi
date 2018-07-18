<?php

/**
 * Handles heavy functionality for Vehicles
 */

namespace App\Services;

use App\ApiResponse;
use App\Vehicle;
use GuzzleHttp\Client;
use \Exception;

class VehicleService
{

    private $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }


    /**
     * Fetch vehicles data from API
     *
     * @param string $modelYear Year of the vehicles to find
     * @param string $manufacturer  manufacture or make of vehicles to find
     * @param string $model model of vehicles
     * @return ApiResponse
     */
    public function fetch(int $modelYear = null, string $manufacturer = null, string $model = null, bool $withRating = false): ApiResponse
    {
        $url = $this->prepareUrl($modelYear, $manufacturer, $model);
        $result = $this->fetchApi($url);

        if ($withRating === true) {
            $result->Results = $this->getRatings($result->Results);
        }

        return new ApiResponse($result->Count, $result->Results);
    }

    /**
     * Format the API url acording to params given
     *
     * @param string $modelYear Year of the vehicles to find
     * @param string $manufacturer  manufacture or make of vehicles to find
     * @param string $model model of vehicles
     * @return string
     */
    private function prepareUrl(int $modelYear, string $manufacturer, string $model): string
    {
        $url = env('API_URL');

        if (empty($url)) {
            throw new Exception("No API_URL defined in .env file", 1);
        }

        $path = sprintf('modelyear/%d/make/%s/model/%s', $modelYear, $manufacturer, $model);
        $url = "$url/$path?format=json";

        return $url;
    }

    // TODO: room for improvement with https://amphp.org/
    /**
     * Get ratings for each vehicle
     *
     * @param array $vehicles   List/array of vehicles
     * @return array
     */
    private function getRatings(array $vehicles): array
    {
        return array_map([$this, 'fetchRating'], $vehicles);
    }

    /**
     * Fetch rating for a given vehicle
     *
     * @param object $vehicle   Vehicles object coming from api or Vehicle class
     * @return Vehicle
     */
    private function fetchRating(object $vehicle): Vehicle
    {
        $crashRating = 0;
        $url = env('API_URL');
        $url = "$url/VehicleId/$vehicle->VehicleId";

        $result = $this->fetchApi($url);

        if (isset($result) && isset($result->Results)) {
            $crashRating = $result->Results[0]->OverallRating;
        }

        $currentVehicle = new Vehicle($vehicle->VehicleId, $vehicle->VehicleDescription);
        $currentVehicle->CrashRating = $crashRating;

        return $currentVehicle;
    }

    /**
     * Send request to vehicles API
     *
     * @param string $url Url to be requested
     * @return object
     */
    private function fetchApi(string $url): object
    {
        $result = $this->httpClient->get($url)->getBody();
        return json_decode($result);
    }
}
