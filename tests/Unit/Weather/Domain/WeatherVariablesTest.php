<?php

namespace Air\Quality\Tests\Unit\Weather\Domain;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Domain\Variable;
use Air\Quality\Weather\Domain\WeatherVariable;
use Air\Quality\Weather\Domain\WeatherVariables;
use DateTime;

class WeatherVariablesTest extends TestCase
{
    private WeatherVariables $weatherVariables;

    private DateTime $startDateTime;

    private DateTime $endDateTime;

    public function setUp(): void
    {
        $this->startDateTime = new DateTime('2023-04-27 19:00:00');
        $this->endDateTime = new DateTime('2023-04-28 19:00:00');
        $this->weatherVariables = new WeatherVariables();
        $this->weatherVariables->append(new WeatherVariable(Variable::CarbonMonoxide, 1, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter2_5, 3, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter10, 4, 'μg/m³', $this->startDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::CarbonMonoxide, 110, 'μg/m³', $this->endDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter2_5, 10, 'μg/m³', $this->endDateTime));
        $this->weatherVariables->append(new WeatherVariable(Variable::ParticulateMatter10, 11, 'μg/m³', $this->endDateTime));
    }

    public function test_filter_by_datetime_now()
    {
        $formatedCurrentDateTime = $this->startDateTime->format('Y-m-d H:i:s');
        $weatherVariables = $this->weatherVariables->filterByDateTime($this->startDateTime);
        $this->assertEquals([$formatedCurrentDateTime => ['Particulate Matter (PM2.5)' => 3, 'Particulate Matter (PM10)' => 4, 'Carbon Monoxide' => 1]], $weatherVariables->getGroupedByDateTime());
        $this->assertEquals(['Particulate Matter (PM2.5)' => 'μg/m³', 'Particulate Matter (PM10)' => 'μg/m³', 'Carbon Monoxide' => 'μg/m³'], $weatherVariables->getUnits());
    }

    public function test_filter_by_datetime_interval()
    {
        $formatedStartDate = $this->startDateTime->format('Y-m-d H:i:s');
        $formatedEndDate = $this->endDateTime->format('Y-m-d H:i:s');
        $weatherVariables = $this->weatherVariables->filterByDateTimeInterval($this->startDateTime, $this->endDateTime);
        $this->assertEquals([$formatedStartDate => ['Particulate Matter (PM2.5)' => 3, 'Particulate Matter (PM10)' => 4, 'Carbon Monoxide' => 1], $formatedEndDate => ['Particulate Matter (PM2.5)' => 10, 'Particulate Matter (PM10)' => 11, 'Carbon Monoxide' => 110.0]], $weatherVariables->getGroupedByDateTime());
        $this->assertEquals(['Particulate Matter (PM2.5)' => 'μg/m³', 'Particulate Matter (PM10)' => 'μg/m³', 'Carbon Monoxide' => 'μg/m³'], $weatherVariables->getUnits());
    }
}
