<?php

namespace Air\Quality\Weather\Infrastructure;

use Air\Quality\Exception\AirQualityResponseException;
use Air\Quality\Weather\Domain\Variable;
use Air\Quality\Weather\Domain\WeatherVariable;
use Air\Quality\Weather\Domain\WeatherVariables;
use DateTime;
use Exception;
use GuzzleHttp\Client;

final readonly class AirQualityClient
{
    public function __construct(private Client $client)
    {
    }

    /**
     * @param  array{'query': array{latitude: float, longitude: float, hourly: string, domains: string, timezone: string, past_days?: int, start_date?: string, end_date?: string, cell_selection?: string}|array{latitude: float, longitude: float, hourly: string, domains: string, timeformat: string, timezone: string, past_days?: int, cell_selection?: string}}  $params
     */
    public function getAirQuality(array $params): WeatherVariables
    {
        try {
            $response = $this->client->get('/v1/air-quality', $params);
            $contents = $response->getBody()->getContents();
            /** @var array{latitude: float, longitude: float, generationtime_ms: float, utc_offset_seconds: int, timezone: string, timezone_abbreviation: string, hourly_units: array<string, string>, hourly: array<string, string[]|int[]|float[]>} $response */
            $response = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            throw new AirQualityResponseException($exception->getMessage());
        }

        return $this->createResponse($response);
    }

    /**
     * @param  array{latitude: float, longitude: float, generationtime_ms: float, utc_offset_seconds: int, timezone: string, timezone_abbreviation: string, hourly_units: array<string, string>, hourly: array<string, string[]|int[]|float[]>}  $airQualityResponse
     */
    private function createResponse(array $airQualityResponse): WeatherVariables
    {
        $weatherVariables = new WeatherVariables();
        $timeToWeatherVariable = $this->mapTimeToWeatherVariable($airQualityResponse);
        foreach ($timeToWeatherVariable as $variable => $timeToWeatherVariable) {
            foreach ($timeToWeatherVariable as $time => $value) {
                $unit = $airQualityResponse['hourly_units'][$variable];
                $weatherVariable = new WeatherVariable(Variable::from($variable), $value, $unit, new DateTime($time));
                $weatherVariables->append($weatherVariable);
            }
        }

        return $weatherVariables;
    }

    /**
     * @param  array{latitude: float, longitude: float, generationtime_ms: float, utc_offset_seconds: int, timezone: string, timezone_abbreviation: string, hourly_units: array<string, string>, hourly: array<string, string[]|int[]|float[]>}  $airQuality
     * @return array<string, array<string, float>>
     */
    private function mapTimeToWeatherVariable(array $airQuality): array
    {
        $timeToWeatherVariable = [];
        /** @var string[] $time */
        $time = $airQuality['hourly']['time'];
        unset($airQuality['hourly']['time']);
        /**
         * @var string $weatherVariable
         * @var float[] $values
         * */
        foreach ($airQuality['hourly'] as $weatherVariable => $values) {
            $timeToWeatherVariable[$weatherVariable] = array_combine($time, $values);
        }

        return $timeToWeatherVariable;
    }
}
