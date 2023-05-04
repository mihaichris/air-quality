<?php

namespace Air\Quality\Tests\Unit\Weather\Domain;

use Air\Quality\Tests\TestCase;
use Air\Quality\Weather\Domain\Variable;
use Air\Quality\Weather\Domain\WeatherVariable;
use DateTime;

class WeatherVariableTest extends TestCase
{
    public function test_null_value_weather_variable()
    {
        $weatherVariable = new WeatherVariable(Variable::CarbonMonoxide, null, 'μg/m³', new DateTime());
        $this->assertNull($weatherVariable->value);
    }

    public function test_not_null_value_weather_variable()
    {
        $currentDateTime = new DateTime();
        $weatherVariable = new WeatherVariable(Variable::CarbonMonoxide, 1, 'μg/m³', $currentDateTime);
        $this->assertEquals(Variable::CarbonMonoxide, $weatherVariable->variable);
        $this->assertEquals(1, $weatherVariable->value);
        $this->assertEquals('μg/m³', $weatherVariable->unit);
        $this->assertEquals($currentDateTime, $weatherVariable->dateTime);
    }
}
