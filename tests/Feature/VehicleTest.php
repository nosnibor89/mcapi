<?php

namespace Test\Feature;

use Test\TestCase;
use Test\Mock\HttpClientMock;
use Mockery;
use App\Services\VehicleService;

class VehicleServiceTest extends TestCase
{
    /**
     * Test endpoints are correctly implemented for GET method
     *
     * @return void
     */
    public function testVehicleGETEndpoints()
    {
        $routes = [
            '/vehicles/2015/Audi/A3',
            '/vehicles/2015/Toyota/Yaris',
            '/vehicles/2015/Ford/Crown Victoria',
            '/vehicles/undefined/Ford/Fusion',
        ];

        foreach ($routes as $key => $route) {
            $response = $this->call('GET', $route);
            $this->assertEquals(200, $response->status(), "Expected HTTP status 200 for $route");
        }
    }

    /**
     * Test endpoints are correctly implemented for POST method
     *
     * @return void
     */
    public function testVehiclePOSTEndpoints()
    {
        $postData = [
            ['modelYear' => '2015', 'manufacturer' => 'Audi', 'model' => 'A3'],
            ['modelYear' => '2015', 'manufacturer' => 'Toyota', 'model' => 'Yaris'],
            ['modelYear' => '2015', 'manufacturer' => 'Ford', 'model' => 'Crown Victoria'],
            ['modelYear' => 'undefined', 'manufacturer' => 'Ford', 'model' => 'Fusion'],
        ];

        foreach ($postData as $key => $data) {
            $response = $this->call('POST', '/vehicles', $data);
            $this->assertEquals(
                200,
                $response->status(),
                sprintf("Expected HTTP status 200 for %s", json_encode($data))
            );
        }
    }

    /**
     * Test not valid routes gets a 404
     *
     * @return void
     */
    public function testWrongVehicleEndpoints()
    {
        $wrongRoute = '/vehicles/undefined/Ford/Fusion/Someotherparam';

        $response = $this->call('GET', $wrongRoute);
        $this->assertEquals(404, $response->status(), "There should not be a route/endpoints for $wrongRoute");
    }

    /**
     * Test not valid routes gets a 404
     *
     * @return void
     */
    public function testGetVehiclesWithFilters()
    {
        // Simulate service response
        $client = HttpClientMock::fakeForVehicles();
        $vehicleService = new VehicleService($client);
        $expectedResponse = $vehicleService->fetch(2015, 'Audi', 'A3');
        
        $service = Mockery::mock(VehicleService::class);
        $service->shouldReceive('fetch')->with(2015, 'Audi', 'A3')->once()->andReturn($expectedResponse);

        // load the mock into the IoC container
        app()->instance(VehicleService::class, $service);
        // Simulate service response

        $this->json('GET', '/vehicles/2015/Audi/A3')
            ->seeJsonEquals([
                'Count' => $expectedResponse->Count,
                'Results' => $expectedResponse->Results
            ]);
    }
}
