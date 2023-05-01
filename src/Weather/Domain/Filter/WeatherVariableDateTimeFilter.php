<?php

namespace Air\Quality\Weather\Domain\Filter;

use DateTime;
use FilterIterator;
use Iterator;
use Air\Quality\Weather\Domain\WeatherVariable;

final class WeatherVariableDateTimeFilter extends FilterIterator
{
    public function __construct(Iterator $iterator, private readonly DateTime $datetime)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        /** @var WeatherVariable $weatherVariable */
        $weatherVariable = $this->getInnerIterator()->current();

        return $weatherVariable->dateTime->format('Y-m-d H') === $this->datetime->format('Y-m-d H');
    }
}
