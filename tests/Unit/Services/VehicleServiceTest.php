<?php

namespace Test\Unit\Services;

use Test\TestCase;

use App\Services\VehicleService;
use Test\Mock\HttpClientMock;

class VehicleServiceTest extends TestCase
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
        $results = $vehicleService->fetch(2015, 'Audi', 'A3');

        $this->assertEquals($results->Count, 4);
        $this->assertEquals($results->Results[0]->VehicleId, 9403);
    }
}
