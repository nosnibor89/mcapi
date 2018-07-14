<?php

namespace Test\Unit\Services;

use Test\TestCase;

use App\Http\Controllers\VehicleController;
use App\Services\VehicleService;
use Mock\HttpClientMock;

class VehicleControllerTest extends TestCase
{
    /**
     * Test that vehicles service can fetch vechcles data.
     *
     * @return void
     */
    public function testFetch()
    {
        $client = HttpClientMock::fakeForVehicles();

        $vehicleService = new VehicleService($client);

        // $controller = new VehicleController();

        print_r(app());

        // $results = $vehicleService->fetch(2015, 'Audi', 'A3');

        $this->assertEquals($results->Count, 4);
        $this->assertEquals($results->Results[0]->VehicleId, 9403);
    }
}
