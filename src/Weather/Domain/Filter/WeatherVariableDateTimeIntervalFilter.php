<?php

namespace Air\Quality\Weather\Domain\Filter;

use DateTime;
use FilterIterator;
use Iterator;
use Air\Quality\Weather\Domain\WeatherVariable;

final class WeatherVariableDateTimeIntervalFilter extends FilterIterator
{
    public function __construct(Iterator $iterator, private readonly DateTime $startDateTime, private readonly DateTime $endDateTime)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        /** @var WeatherVariable $weatherVariable */
        $weatherVariable = $this->getInnerIterator()->current();
        $formattedStartDateTime = $this->startDateTime->format('Y-m-d H');
        $formattedEndDateTime = $this->endDateTime->format('Y-m-d H');
        $formattedWeatherVariableDateTime = $weatherVariable->dateTime->format('Y-m-d H');
        if ($formattedWeatherVariableDateTime < $formattedStartDateTime) {
            return false;
        }

        return $formattedWeatherVariableDateTime <= $formattedEndDateTime;
    }
}
