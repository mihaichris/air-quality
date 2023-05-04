<?php

namespace Air\Quality\Tests\Unit\Weather\Infrastructure;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Infrastructure\AirQualityResponse;

class AirQualityResponseTest extends TestCase
{
    public function test_empty_response()
    {
        $response = new AirQualityResponse([], []);
        $this->assertEmptyAirQualityResponse($response);
    }

    public function test_not_empty_response()
    {
        $response = new AirQualityResponse(['pm25' => [1, 2, 3]], ['pm25' => 'units']);
        $this->assertEquals(['pm25' => [1, 2, 3]], $response->hourly);
        $this->assertEquals(['pm25' => 'units'], $response->units);
    }
}
