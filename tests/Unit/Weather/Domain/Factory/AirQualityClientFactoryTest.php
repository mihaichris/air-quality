<?php

namespace Air\Quality\Tests\Unit\Weather\Domain\Factory;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Domain\Factory\AirQualityClientFactory;
use Air\Quality\Weather\Infrastructure\AirQualityClient;

class AirQualityClientFactoryTest extends TestCase
{
    public function test_get_client_with_handler()
    {
        $airQualityClientFactory = new AirQualityClientFactory($this->getMockHttpHandler());
        $this->assertInstanceOf(AirQualityClient::class, $airQualityClientFactory->getClient());
    }

    public function test_get_client_without_handler()
    {
        $airQualityClientFactory = new AirQualityClientFactory();
        $this->assertInstanceOf(AirQualityClient::class, $airQualityClientFactory->getClient());
    }
}
