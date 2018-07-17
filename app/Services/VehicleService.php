<?php

namespace App\Services;

use App\VehicleResponse;
use GuzzleHttp\Client;
use \Exception;

class VehicleService
{

    private $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }


    public function fetch(int $modelYear = null, string $manufacturer = null, string $model = null): VehicleResponse
    {
        $url = $this->prepareUrl($modelYear, $manufacturer, $model);

        $result = $this->httpClient->get($url)->getBody();
        $result = json_decode($result);

        return new VehicleResponse($result->Count, $result->Results);
    }

    private function prepareUrl(int $modelYear, string $manufacturer, string $model): string
    {
        $url = env('API_URL');

        if (empty($url)) {
            throw new Exception("No API_URL defined in .env file", 1);
        }

        $path = sprintf('modelyear/%d/make/%s/model/%s', $modelYear, $manufacturer, $model);
        $url = "$url/$path";

        return $url;
    }
}
