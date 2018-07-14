<?php

namespace Test\Feature;

use Test\TestCase;

class VehicleServiceTest extends TestCase
{
    /**
     * Test endpoints are correcly implemented
     *
     * @return void
     */
    public function testVehicleEndpoints()
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

    //  /**
    //  * Test not valid routes gets a 404
    //  *
    //  * @return void
    //  */
    // public function testVehicleEndpointJson()
    // {
    //     $wrongRoute = '/vehicles/undefined/Ford/Fusion/Someotherparam';

    //     $response = $this->call('GET', $wrongRoute);
    //     $this->assertEquals(404, $response->status(), "There should not be a route/endpoints for $wrongRoute");
    // }
}
