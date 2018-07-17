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

     /**
     * Test that received data can be formatted properly
     *
     * @return void
     */
    public function testVehicleDataIsFormatted()
    {
        $client = HttpClientMock::fakeForVehicles();

        $vehicleService = new VehicleService($client);
        $results = $vehicleService->fetch(2015, 'Audi', 'A3');

        $sampleResult = $results->Results[0];

        $correctPropertyExists = property_exists($sampleResult, 'Description');
        $wrongPropertyExists = property_exists($sampleResult, 'VehicleDescription');

        $this->assertTrue($correctPropertyExists, "Property Description should exists");
        $this->assertFalse($wrongPropertyExists, "Property VehicleDescription shouldn't exists");
    }
}
