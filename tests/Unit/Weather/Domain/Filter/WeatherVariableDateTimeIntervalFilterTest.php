<?php

namespace Air\Quality\Tests\Unit\Weather\Domain\Filter;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Domain\Filter\WeatherVariableDateTimeIntervalFilter;
use Air\Quality\Weather\Domain\Variable;
use Air\Quality\Weather\Domain\WeatherVariable;
use Air\Quality\Weather\Domain\WeatherVariables;
use DateTime;

class WeatherVariableDateTimeIntervalFilterTest extends TestCase
{
    private DateTime $startDateTime;

    private DateTime $endDateTime;

    private WeatherVariables $weatherVariables;

    public function setUp(): void
    {
        $this->startDateTime = new DateTime('2023-04-27 19:00:00');
        $this->endDateTime = new DateTime('2023-04-25 19:00:00');
        $this->weatherVariables = new WeatherVariables();
        $this->weatherVariables->append(new WeatherVariable(Variable::CarbonMonoxide, 1, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter2_5, 3, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter10, 4, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::CarbonMonoxide, 110, 'μg/m³', $this->endDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter2_5, 10, 'μg/m³', $this->endDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter10, 11, 'μg/m³', $this->endDateTime));
    }

    public function test_filter_with_empty_iterator()
    {
        $emptyWeatherVariables = new WeatherVariables();
        $iterator = new WeatherVariableDateTimeIntervalFilter($emptyWeatherVariables->getIterator(), $this->startDateTime, $this->endDateTime);
        $this->assertEmpty($iterator->current());
    }

    public function test_filter_with_iterator()
    {
        $expectedWeatherVariables = new WeatherVariables();
        $expectedWeatherVariables->append(new WeatherVariable(Variable::CarbonMonoxide, 1, 'μg/m³', $this->startDateTime));
        $expectedWeatherVariables->append(new WeatherVariable(Variable::ParticulateMatter2_5, 3, 'μg/m³', $this->startDateTime));
        $expectedWeatherVariables->append(new WeatherVariable(Variable::ParticulateMatter10, 4, 'μg/m³', $this->startDateTime));
        $actualWeatherVariables = new WeatherVariables();
        $iterator = new WeatherVariableDateTimeIntervalFilter($this->weatherVariables->getIterator(), new DateTime('2023-04-26 19:00:00'), new DateTime('2023-04-27 19:00:00'));
        /** @var WeatherVariable $weatherVariable */
        foreach ($iterator as $weatherVariable) {
            $actualWeatherVariables->append($weatherVariable);
        }
        $this->assertEquals($expectedWeatherVariables, $actualWeatherVariables);
    }
}
