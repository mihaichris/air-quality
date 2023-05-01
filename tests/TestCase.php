<?php

namespace Air\Quality\Tests;

use Air\Quality\AirQuality;
use Air\Quality\Weather\Infrastructure\AirQualityResponse;
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
        return '{"latitude":44.449997,"longitude":26.150002,"generationtime_ms":0.5360841751098633,"utc_offset_seconds":10800,"timezone":"Europe/Bucharest","timezone_abbreviation":"EEST","hourly_units":{"time":"iso8601","pm10":"μg/m³","pm2_5":"μg/m³","carbon_monoxide":"μg/m³"},"hourly":{"time":["2023-04-25T00:00","2023-04-25T01:00","2023-04-25T02:00","2023-04-25T03:00","2023-04-25T04:00","2023-04-25T05:00","2023-04-25T06:00","2023-04-25T07:00","2023-04-25T08:00","2023-04-25T09:00","2023-04-25T10:00","2023-04-25T11:00","2023-04-25T12:00","2023-04-25T13:00","2023-04-25T14:00","2023-04-25T15:00","2023-04-25T16:00","2023-04-25T17:00","2023-04-25T18:00","2023-04-25T19:00","2023-04-25T20:00","2023-04-25T21:00","2023-04-25T22:00","2023-04-25T23:00","2023-04-26T00:00","2023-04-26T01:00","2023-04-26T02:00","2023-04-26T03:00","2023-04-26T04:00","2023-04-26T05:00","2023-04-26T06:00","2023-04-26T07:00","2023-04-26T08:00","2023-04-26T09:00","2023-04-26T10:00","2023-04-26T11:00","2023-04-26T12:00","2023-04-26T13:00","2023-04-26T14:00","2023-04-26T15:00","2023-04-26T16:00","2023-04-26T17:00","2023-04-26T18:00","2023-04-26T19:00","2023-04-26T20:00","2023-04-26T21:00","2023-04-26T22:00","2023-04-26T23:00","2023-04-27T00:00","2023-04-27T01:00","2023-04-27T02:00","2023-04-27T03:00","2023-04-27T04:00","2023-04-27T05:00","2023-04-27T06:00","2023-04-27T07:00","2023-04-27T08:00","2023-04-27T09:00","2023-04-27T10:00","2023-04-27T11:00","2023-04-27T12:00","2023-04-27T13:00","2023-04-27T14:00","2023-04-27T15:00","2023-04-27T16:00","2023-04-27T17:00","2023-04-27T18:00","2023-04-27T19:00","2023-04-27T20:00","2023-04-27T21:00","2023-04-27T22:00","2023-04-27T23:00","2023-04-28T00:00","2023-04-28T01:00","2023-04-28T02:00","2023-04-28T03:00","2023-04-28T04:00","2023-04-28T05:00","2023-04-28T06:00","2023-04-28T07:00","2023-04-28T08:00","2023-04-28T09:00","2023-04-28T10:00","2023-04-28T11:00","2023-04-28T12:00","2023-04-28T13:00","2023-04-28T14:00","2023-04-28T15:00","2023-04-28T16:00","2023-04-28T17:00","2023-04-28T18:00","2023-04-28T19:00","2023-04-28T20:00","2023-04-28T21:00","2023-04-28T22:00","2023-04-28T23:00","2023-04-29T00:00","2023-04-29T01:00","2023-04-29T02:00","2023-04-29T03:00","2023-04-29T04:00","2023-04-29T05:00","2023-04-29T06:00","2023-04-29T07:00","2023-04-29T08:00","2023-04-29T09:00","2023-04-29T10:00","2023-04-29T11:00","2023-04-29T12:00","2023-04-29T13:00","2023-04-29T14:00","2023-04-29T15:00","2023-04-29T16:00","2023-04-29T17:00","2023-04-29T18:00","2023-04-29T19:00","2023-04-29T20:00","2023-04-29T21:00","2023-04-29T22:00","2023-04-29T23:00"],"pm10":[18.7,17.8,17.2,16.7,17.1,17.3,19.3,21.0,23.5,23.6,21.2,20.5,20.1,18.3,14.7,13.2,10.4,9.4,9.2,9.3,10.2,15.5,13.2,10.6,10.2,10.7,11.6,12.5,11.5,11.4,15.5,17.0,19.6,22.6,19.5,11.9,8.7,5.2,5.4,6.6,5.9,5.9,5.8,7.2,9.5,12.1,13.3,13.1,13.0,13.6,14.4,15.1,16.3,17.0,18.3,19.7,20.6,19.1,16.8,17.1,19.0,18.5,15.8,13.5,13.1,12.5,12.2,13.6,16.2,19.3,24.5,24.7,18.3,15.9,14.8,14.9,15.1,16.0,17.5,19.3,20.1,19.1,18.9,18.7,18.3,18.9,19.5,20.2,19.1,19.1,18.3,18.5,19.9,20.7,22.7,26.4,27.8,29.0,28.7,26.0,29.8,32.4,34.5,36.7,34.1,28.0,25.3,22.3,19.6,19.1,18.9,18.7,18.6,18.5,17.9,18.1,19.5,19.4,18.6,17.4],"pm2_5":[15.9,15.2,14.0,13.6,13.4,13.8,17.5,19.2,21.9,22.2,17.3,17.1,17.3,17.1,14.0,11.5,9.1,8.8,8.0,8.3,9.3,14.4,12.4,9.9,9.6,8.6,9.5,10.6,9.6,10.3,14.0,15.4,17.8,20.0,15.8,10.2,6.1,4.4,4.3,5.5,4.8,5.1,5.4,6.5,8.7,11.2,11.8,11.4,11.4,11.3,11.0,11.4,12.5,13.8,14.9,15.6,17.8,16.2,13.2,13.8,17.3,16.1,13.1,11.7,11.6,11.3,11.0,12.7,14.0,17.5,22.1,21.8,16.2,13.4,13.2,13.0,13.2,13.6,14.1,15.6,16.5,16.5,16.3,15.4,15.9,16.7,17.4,16.8,16.5,15.8,14.9,15.3,15.9,16.4,20.3,21.3,22.8,23.2,23.5,22.2,20.7,22.5,24.0,25.6,23.7,19.4,17.5,15.4,13.4,13.1,13.0,12.9,12.8,12.7,12.3,12.5,13.4,13.4,12.8,12.0],"carbon_monoxide":[171.0,165.0,162.0,163.0,168.0,173.0,182.0,206.0,223.0,192.0,187.0,182.0,184.0,195.0,187.0,170.0,160.0,164.0,166.0,165.0,171.0,190.0,183.0,173.0,171.0,165.0,168.0,168.0,168.0,174.0,187.0,220.0,257.0,227.0,245.0,188.0,167.0,164.0,160.0,162.0,162.0,159.0,157.0,167.0,172.0,174.0,177.0,168.0,165.0,162.0,150.0,150.0,148.0,145.0,152.0,170.0,172.0,147.0,151.0,160.0,160.0,159.0,158.0,149.0,149.0,154.0,162.0,175.0,202.0,226.0,238.0,221.0,170.0,136.0,134.0,133.0,134.0,138.0,148.0,168.0,182.0,188.0,159.0,146.0,139.0,143.0,142.0,139.0,141.0,142.0,143.0,174.0,206.0,197.0,222.0,224.0,213.0,205.0,202.0,183.0,231.0,248.0,261.0,260.0,252.0,235.0,214.0,189.0,160.0,148.0,141.0,136.0,132.0,129.0,135.0,154.0,180.0,204.0,202.0,190.0]}}';
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
            foreach ($values as $time => $value) {
                $this->assertGreaterThanOrEqual(date('Y-m-d H:00:00', strtotime($startDateTime)), $time);
                $this->assertLessThanOrEqual(date('Y-m-d H:00:00', strtotime($endDateTime)), $time);
            }
        }
    }
}
