<?php

declare(strict_types=1);

namespace Air\Quality\Tests\Unit\Weather\Infrastructure;

use Air\Quality\Exception\AirQualityResponseException;
use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Infrastructure\AirQualityClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;

class AirQualityClientTest extends TestCase
{
    private AirQualityClient $airQualityClient;

    private Client|MockObject $client;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->airQualityClient = new AirQualityClient($this->client);
    }

    public function test_get_air_quality_with_empty_response_with_keys()
    {
        $mockResponse = $this->getMockHttpResponse($this->getOpenMeteoAirQualityEmptyKeysMockResponseBody());
        $params = [];
        $this->client
            ->method('get')
            ->with('/v1/air-quality', $params)
            ->willReturn($mockResponse);
        $weatherVariables = $this->airQualityClient->getAirQuality($params);
        $this->assertEmpty($weatherVariables);
    }

    public function test_get_air_quality_response()
    {
        $mockResponse = $this->getMockHttpResponse($this->getOpenMeteoAirQualityMockResponseBody());
        $params = [];
        $this->client
            ->method('get')
            ->with('/v1/air-quality', $params)
            ->willReturn($mockResponse);
        $weatherVariables = $this->airQualityClient->getAirQuality($params);
        $this->assertNotEmpty($weatherVariables);
        $this->assertEquals(
            [
                '2023-04-25 00:00:00' => ['Particulate Matter (PM10)' => 18.7, 'Particulate Matter (PM2.5)' => 15.9, 'Carbon Monoxide' => 171.0],
                '2023-04-25 01:00:00' => ['Particulate Matter (PM10)' => 17.8, 'Particulate Matter (PM2.5)' => 15.2, 'Carbon Monoxide' => 165.0],
                '2023-04-25 02:00:00' => ['Particulate Matter (PM10)' => 17.2, 'Particulate Matter (PM2.5)' => 14.0, 'Carbon Monoxide' => 162.0],
                '2023-04-25 03:00:00' => ['Particulate Matter (PM10)' => 16.7, 'Particulate Matter (PM2.5)' => 13.6, 'Carbon Monoxide' => 163.0],
                '2023-04-25 04:00:00' => ['Particulate Matter (PM10)' => 17.1, 'Particulate Matter (PM2.5)' => 13.4, 'Carbon Monoxide' => 168.0]
            ],
            $weatherVariables->getGroupedByDateTime()
        );
        $this->assertEquals(
            [
                'Particulate Matter (PM10)' => 'μg/m³',
                'Particulate Matter (PM2.5)' => 'μg/m³',
                'Carbon Monoxide' => 'μg/m³',
            ],
            $weatherVariables->getUnits()
        );
    }

    public function test_air_quality_api_throws_exception_will_rethrow_custom_exception()
    {
        $exception = new AirQualityResponseException('Error API');
        $this->expectExceptionObject($exception);
        $this->client->method('get')->willThrowException($exception);
        $this->airQualityClient->getAirQuality([]);
    }
}
