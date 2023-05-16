<?php

namespace Air\Quality\Weather\Domain;

use Air\Quality\Weather\Domain\Filter\WeatherVariableDateTimeFilter;
use Air\Quality\Weather\Domain\Filter\WeatherVariableDateTimeIntervalFilter;
use ArrayObject;
use DateTime;

final class WeatherVariables extends ArrayObject
{
    public function filterByDateTime(DateTime $dateTime): self
    {
        $weatherVariables = new WeatherVariables();
        $weatherVariableDateTimeFilter = new WeatherVariableDateTimeFilter($this->getIterator(), $dateTime);
        /** @var WeatherVariable $weatherVariable */
        foreach ($weatherVariableDateTimeFilter as $weatherVariable) {
            $weatherVariables->append($weatherVariable);
        }

        return $weatherVariables;
    }

    public function filterByDateTimeInterval(DateTime $startDate, DateTime $endDate): self
    {
        $weatherVariables = new WeatherVariables();
        $weatherVariableDateTimeIntervalFilter = new WeatherVariableDateTimeIntervalFilter($this->getIterator(), $startDate, $endDate);
        /** @var WeatherVariable $weatherVariable */
        foreach ($weatherVariableDateTimeIntervalFilter as $weatherVariable) {
            $weatherVariables->append($weatherVariable);
        }

        return $weatherVariables;
    }

    /** @return array<string, array<string, float|null>> */
    public function getGroupedByDateTime(): array
    {
        $groupedByDateTime = [];
        /** @var WeatherVariable $weatherVariable */
        foreach ($this->getIterator() as $weatherVariable) {
            $dateTime = $weatherVariable->dateTime->format('Y-m-d H:i:s');
            $label = $weatherVariable->variable->getLabel();
            $groupedByDateTime[$dateTime][$label] = $weatherVariable->value;
        }

        return $groupedByDateTime;
    }

    /** @return array<string, string> */
    public function getUnits(): array
    {
        $units = [];
        /** @var WeatherVariable $weatherVariable */
        foreach ($this->getIterator() as $weatherVariable) {
            $units[$weatherVariable->variable->getLabel()] = $weatherVariable->unit;
        }

        return $units;
    }
}
