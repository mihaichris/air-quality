<?php

namespace Air\Quality\Weather\Infrastructure;

final readonly class AirQualityResponse
{
    /**
     * @param  array<string, array<string, float|null>>  $hourly
     * @param  array<string, string>  $units
     */
    public function __construct(public array $hourly, public array $units)
    {
    }
}
