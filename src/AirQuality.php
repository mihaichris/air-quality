<?php

namespace Air\Quality;

use Air\Quality\Weather\Variables;
use DateTimeZone;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class AirQuality
{
    private array $weatherVariables = Variables::cases();

    private Client $httpClient;

    private string $domain = 'auto';

    private string $timeFormat = 'iso8601';

    private DateTimeZone $timezone = null;

    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly $handler = null
    ) {
        $this->httpClient = $this->createHttpClient($handler);
    }

    public function domain(string $domain): self
    {
        return $this->domain = $domain;

        return $this;
    }

    public function timezone(DateTimeZone $dateTimeZone): self
    {
        $this->timezone = $dateTimeZone;

        return $this;
    }

    public function timeFormat(string $timeFormat): self
    {
        $this->timeFormat = $timeFormat;

        return $this;
    }

    /** @param  string[]  $weatherVariables  */
    public function with(array $weatherVariables): self
    {
        $this->weatherVariables = $weatherVariables;

        return $this;
    }

    public function now()
    {
        $params = $this->buildParams();
        $response = $this->httpClient->get('/v1/air-quality', $params);
    }

    private function buildParams(): array
    {
        return [
            'query' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'hourly' => implode(',', $this->weatherVariables),
                'domains' => $this->domain,
                'timeformat' => $this->timeFormat,
                'timezone' => $this->timezone,
                'past_days'
            ]
        ];
    }

    private function createHttpClient($handler = null): ClientInterface
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
