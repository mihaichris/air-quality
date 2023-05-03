<?php

namespace Air\Quality\Tests;

use Air\Quality\AirQuality;
use Air\Quality\Weather\Infrastructure\AirQualityResponse;
use DateInterval;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class TestCase extends BaseTestCase
{
    public function getAirQualityMock(Response $response)
    {
        return new AirQuality(44.43, 26.11, $this->getMockHttpHandler($response));
    }

    public function getOpenMeteoAirQualityMockResponseBody(): string
    {
        return '{"latitude":44.449997,"longitude":26.150002,"generationtime_ms":0.5360841751098633,"utc_offset_seconds":10800,"timezone":"Europe/Bucharest","timezone_abbreviation":"EEST","hourly_units":{"time":"iso8601","pm10":"μg/m³","pm2_5":"μg/m³","carbon_monoxide":"μg/m³"},"hourly":{"time":["2023-04-25T00:00","2023-04-25T01:00","2023-04-25T02:00","2023-04-25T03:00","2023-04-25T04:00"],"pm10":[18.7,17.8,17.2,16.7,17.1],"pm2_5":[15.9,15.2,14.0,13.6,13.4],"carbon_monoxide":[171.0,165.0,162.0,163.0,168.0]}}';
    }

    public function getOpenMeteoAirQualityEmptyKeysMockResponseBody(): string
    {
        return '{"latitude":44.449997,"longitude":26.150002,"generationtime_ms":0.5360841751098633,"utc_offset_seconds":10800,"timezone":"Europe/Bucharest","timezone_abbreviation":"EEST","hourly_units":{"time":"iso8601","pm10":"μg/m³","pm2_5":"μg/m³","carbon_monoxide":"μg/m³"},"hourly":{"time":[],"pm10":[],"pm2_5":[],"carbon_monoxide":[]}}';
    }

    public function getMockHttpHandler(?Response $airQualityResponse = null): HandlerStack
    {
        $handler = new MockHandler();
        if ($airQualityResponse !== null) {
            $handler->append($airQualityResponse);
        }

        return HandlerStack::create($handler);
    }

    public function assertIsAirQualityResponseInstance($expected)
    {
        $this->assertInstanceOf(AirQualityResponse::class, $expected);
    }

    public function assertAirQualityResponsePropertiesAreNotEmpty(AirQualityResponse $airQualityResponse)
    {
        $this->assertNotEmpty($airQualityResponse->hourly);
        $this->assertNotEmpty($airQualityResponse->units);
    }

    public function assertAirQualityResponsePropertiesAreEmpty(AirQualityResponse $airQualityResponse)
    {
        $this->assertEmpty($airQualityResponse->hourly);
        $this->assertEmpty($airQualityResponse->units);
    }

    public function getMockHttpResponse(string $body): MockObject
    {
        /** @var MockObject $mockResponse */
        $mockResponse = $this->createMock(ResponseInterface::class);
        /** @var MockObject $mockContents */
        $mockContents = $this->createMock(StreamInterface::class);
        $mockContents->method('getContents')->willReturn($body);
        $mockResponse->method('getBody')->willReturn($mockContents);

        return $mockResponse;
    }

    public function assertEmptyAirQualityResponse(AirQualityResponse $airQualityResponse)
    {
        $this->assertEmpty($airQualityResponse->hourly);
        $this->assertEmpty($airQualityResponse->units);
    }

    public function assertResponseIsBetweenDates(AirQualityResponse $airQualityResponse, string $startDateTime, string $endDateTime)
    {
        foreach ($airQualityResponse->hourly as $values) {
            $this->assertEquals(array_key_first($values), date('Y-m-d H:00:00', strtotime($startDateTime)));
            $this->assertEquals(array_key_last($values), date('Y-m-d H:00:00', strtotime($endDateTime)));
        }
        foreach ($airQualityResponse->hourly as $values) {
            foreach ($values as $time => $value) {
                $this->assertGreaterThanOrEqual(date('Y-m-d H:00:00', strtotime($startDateTime)), $time);
                $this->assertLessThanOrEqual(date('Y-m-d H:00:00', strtotime($endDateTime)), $time);
            }
        }
    }

    public function assertResponseIsFromPastDays(AirQualityResponse $airQualityResponse, int $days, string $timezone)
    {
        $startDateTime = new DateTime('today', new DateTimeZone($timezone));
        $startDateTime->sub(DateInterval::createFromDateString(sprintf('%d days', $days)));
        $endDateTime = new DateTime('now', new DateTimeZone($timezone));
        $this->assertResponseIsBetweenDates($airQualityResponse, $startDateTime->format('Y-m-d H:i:s'), $endDateTime->format('Y-m-d H:i:s'));
    }
}
