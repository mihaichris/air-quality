<?php

namespace Air\Quality\Weather\Domain;

use DateTime;

final readonly class WeatherVariable
{
    public function __construct(public Variable $variable, public ?float $value, public string $unit, public DateTime $dateTime)
    {
    }
}
