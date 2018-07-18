<?php

namespace Test\Feature;

use Test\TestCase;
use Test\Mock\HttpClientMock;
use Mockery;
use App\Services\VehicleService;

class VehicleServiceTest extends TestCase
{
    /**
     * Test endpoint is correctly implemented for GET method
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
     * Test endpoint is correctly implemented for POST method
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
     * Test GET /vehicles endpoint with filters
     *
     * @return void
     */
    public function testGETVehiclesWithFilters()
    {
        // Simulate service response
        $client = HttpClientMock::fakeForVehicles();
        $vehicleService = new VehicleService($client);
        $expectedResponse = $vehicleService->fetch(2015, 'Audi', 'A3', false);
        
        $service = Mockery::mock(VehicleService::class);
        $service->shouldReceive('fetch')->with(2015, 'Audi', 'A3', false)->once()->andReturn($expectedResponse);

        // load the mock into the IoC container
        app()->instance(VehicleService::class, $service);
        // Simulate service response

        $this->json('GET', '/vehicles/2015/Audi/A3')
            ->seeJsonEquals([
                'Count' => $expectedResponse->Count,
                'Results' => $expectedResponse->Results
            ]);
    }

      /**
     * Test POST /vehicles endpoint with filters
     *
     * @return void
     */
    public function testPOSTVehiclesWithFilters()
    {
        // Simulate service response
        $client = HttpClientMock::fakeForVehicles();
        $vehicleService = new VehicleService($client);
        $expectedResponse = $vehicleService->fetch(2015, 'Audi', 'A3', false);
        
        $service = Mockery::mock(VehicleService::class);
        $service->shouldReceive('fetch')->with(2015, 'Audi', 'A3', false)->once()->andReturn($expectedResponse);

        // load the mock into the IoC container
        app()->instance(VehicleService::class, $service);
        // Simulate service response

        $this->json('POST', '/vehicles', ['modelYear' => '2015', 'manufacturer' => 'Audi', 'model' => 'A3'])
            ->seeJsonEquals([
                'Count' => $expectedResponse->Count,
                'Results' => $expectedResponse->Results
            ]);
    }

    /**
     * Test GET /vehicles endpoint works correctly with bad params provided
     *
     * @return void
     */
    public function testGETVehiclesEndpointWithBadQueryParam()
    {
        // Simulate service response
        $client = HttpClientMock::fakeForVehicles();
        $vehicleService = new VehicleService($client);
        $expectedResponse = $vehicleService->fetch(2015, 'Audi', 'A3', false);
        
        $service = Mockery::mock(VehicleService::class);
        $service->shouldReceive('fetch')->with(2015, 'Audi', 'A3', false)->once()->andReturn($expectedResponse);

        // load the mock into the IoC container
        app()->instance(VehicleService::class, $service);
        // Simulate service response

        // Should get same response as if there is no withRating param
        $this->json('GET', '/vehicles/2015/Audi/A3?withRating=mycar')
            ->seeJsonEquals([
                'Count' => $expectedResponse->Count,
                'Results' => $expectedResponse->Results
            ]);
    }

     /**
     * Test POST /vehicles endpoint works correctly with bad params provided
     *
     * @return void
     */
    public function testPOSTVehiclesWithBadQueryParams()
    {
        $this->json('POST', '/vehicles', ['modelYear' => '2015', 'manufacturer' => 'Audi'])
            ->seeJson([
                'Count' => 0,
                'Results' => []
            ]);
    }

    /**
     * Test POST /vehicles endpoints with ratings
     *
     * @return void
     */
    public function testGETVehiclesWithRatings()
    {
        // Simulate service response
        $client = HttpClientMock::fakeForRatings();
        $vehicleService = new VehicleService($client);
        $expectedResponse = $vehicleService->fetch(2015, 'Audi', 'A3', true);
        
        $service = Mockery::mock(VehicleService::class);
        $service->shouldReceive('fetch')->with(2015, 'Audi', 'A3', true)->once()->andReturn($expectedResponse);

        // load the mock into the IoC container
        app()->instance(VehicleService::class, $service);
        // Simulate service response

        $this->json('GET', '/vehicles/2015/Audi/A3?withRating=true')
            ->seeJsonEquals([
                'Count' => $expectedResponse->Count,
                'Results' => $expectedResponse->Results
            ]);
    }
}
