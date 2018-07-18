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
        $results = $vehicleService->fetch(2015, 'Audi', 'A3', false);

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
        $results = $vehicleService->fetch(2015, 'Audi', 'A3', false);

        $sampleResult = $results->Results[0];

        $correctPropertyExists = property_exists($sampleResult, 'Description');
        $wrongPropertyExists = property_exists($sampleResult, 'VehicleDescription');

        $this->assertTrue($correctPropertyExists, "Property Description should exists");
        $this->assertNotEmpty(
            $sampleResult->Description,
            "Property Description is empty for $sampleResult->VehicleId"
        );
        $this->assertFalse($wrongPropertyExists, "Property VehicleDescription shouldn't exists");
    }


    /**
     * Test that CrashRating can be fetched for a group of vehicles
     *
     * @return void
     */
    public function testFetchRatings()
    {
        $client = HttpClientMock::fakeForRatings();

        $vehicleService = new VehicleService($client);
        $results = $vehicleService->fetch(2015, 'Audi', 'A3', true);

        $sampleResult = $results->Results[0];
        $sampleResult = $results->Results[0];

        foreach ($results->Results as $key => $result) {
            $propertyExists = property_exists($sampleResult, 'CrashRating');

            $this->assertTrue(
                $propertyExists,
                "Property CrashRating should exists for $result->VehicleId -- $result->CrashRating given"
            );
            $this->assertNotEmpty(
                $result->CrashRating,
                "Property CrashRating is empty for $result->VehicleId -- $result->CrashRating given"
            );
        }
    }
}
