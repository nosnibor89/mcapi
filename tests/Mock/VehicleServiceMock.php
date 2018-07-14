<?php

// namespace Mock;

// use GuzzleHttp\Client;
// use GuzzleHttp\Handler\MockHandler;
// use GuzzleHttp\HandlerStack;
// use GuzzleHttp\Psr7\Response;

// class VehicleServiceMock
// {
//     public static function fake(): Client
//     {
//          // Mocking API Response
//          $apiResponseBody = '
//          {
//              "Count": 4,
//              "Message": "Results returned successfully",
//              "Results": [
//                {
//                  "VehicleDescription": "2015 Audi A3 4 DR AWD",
//                  "VehicleId": 9403
//                },
//                {
//                  "VehicleDescription": "2015 Audi A3 4 DR FWD",
//                  "VehicleId": 9408
//                },
//                {
//                  "VehicleDescription": "2015 Audi A3 C AWD",
//                  "VehicleId": 9405
//                },
//                {
//                  "VehicleDescription": "2015 Audi A3 C FWD",
//                  "VehicleId": 9406
//                }
//              ]
//            }';
 
//          $mock = new MockHandler([
//              new Response(200, ['X-Foo' => 'Bar'], $apiResponseBody),
//          ]);
//          $handler = HandlerStack::create($mock);
//          $client = new Client(['handler' => $handler]);

//          return $client;
//     }
// }
