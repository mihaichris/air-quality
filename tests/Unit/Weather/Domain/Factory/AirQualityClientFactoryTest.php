<?php

namespace Air\Quality\Tests\Unit\Weather\Domain\Factory;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Domain\Factory\AirQualityClientFactory;
use Air\Quality\Weather\Infrastructure\AirQualityClient;
use GuzzleHttp\Client;

class AirQualityClientFactoryTest extends TestCase
{
    public function test_get_client_with_handler()
    {
        $mockHandler = $this->getMockHttpHandler();
        $client = new AirQualityClient(new Client(['handler' => $mockHandler, 'base_uri' => 'https://air-quality-api.open-meteo.com/']));
        $airQualityClientFactory = new AirQualityClientFactory($mockHandler);
        $this->assertInstanceOf(AirQualityClient::class, $airQualityClientFactory->getClient());
        $this->assertEquals($client, $airQualityClientFactory->getClient());
    }

    public function test_get_client_without_handler()
    {
        $client = new AirQualityClient(new Client(['base_uri' => 'https://air-quality-api.open-meteo.com/']));
        $airQualityClientFactory = new AirQualityClientFactory();
        $this->assertInstanceOf(AirQualityClient::class, $airQualityClientFactory->getClient());
        $this->assertEquals($client, $airQualityClientFactory->getClient());
    }
}
