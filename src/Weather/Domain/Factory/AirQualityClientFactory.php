<?php

namespace Air\Quality\Weather\Domain\Factory;

use Air\Quality\Weather\Infrastructure\AirQualityClient;
use GuzzleHttp\Client;

final class AirQualityClientFactory
{
    private readonly Client $client;

    public function __construct(?callable $handler = null)
    {
        $this->client = $this->createClient($handler);
    }

    public function getClient(): AirQualityClient
    {
        return new AirQualityClient($this->client);
    }

    private function createClient(?callable $handler = null): Client
    {
        $options = [
            'base_uri' => 'https://air-quality-api.open-meteo.com/',
        ];
        if (null !== $handler) {
            $options['handler'] = $handler;
        }

        return new Client($options);
    }
}
