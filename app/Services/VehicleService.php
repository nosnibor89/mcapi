<?php

/**
 * Handles heavy functionality for Vehicles
 */

namespace App\Services;

use App\VehicleResponse;
use App\VehicleResult;
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
     * @return VehicleResponse
     */
    public function fetch(int $modelYear = null, string $manufacturer = null, string $model = null, bool $withRating = false): VehicleResponse
    {
        $url = $this->prepareUrl($modelYear, $manufacturer, $model);
        $result = $this->fetchApi($url);

        if ($withRating) {
            $result->Results = $this->getRatings($result->Results);
        }

        return new VehicleResponse($result->Count, $result->Results);
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
    private function getRatings(array $results): array
    {
        return array_map([$this, 'fetchRating'], $results);
    }

    private function fetchRating(object $vehicle): VehicleResult
    {
        $crashRating = 0;
        $url = env('API_URL');
        $url = "$url/VehicleId/$vehicle->VehicleId";

        $result = $this->fetchApi($url);

        if (isset($result) && isset($result->Results)) {
            $crashRating = $result->Results[0]->OverallRating;
        }

        $currentVehicle = new VehicleResult($vehicle->VehicleId, $vehicle->VehicleDescription);
        $currentVehicle->CrashRating = $crashRating;

        return $currentVehicle;
    }

    private function fetchApi(string $url): object
    {
        $result = $this->httpClient->get($url)->getBody();
        return json_decode($result);
    }
}
