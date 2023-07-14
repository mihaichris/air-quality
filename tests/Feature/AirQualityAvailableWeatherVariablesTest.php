<?php

use Air\Quality\AirQuality;
use Air\Quality\Tests\TestCase;

class AirQualityAvailableWeatherVariablesTest extends TestCase
{
    public function test_get_available_weather_variables()
    {
        $expectedWeatherVariables = [
            'pm10' => 'Particulate Matter (PM10)',
            'pm2_5' => 'Particulate Matter (PM2.5)',
            'carbon_monoxide' => 'Carbon Monoxide',
            'nitrogen_dioxide' => 'Nitrogen Dioxide',
            'sulphur_dioxide' => 'Sulphur Dioxide',
            'ozone' => 'Ozone',
            'aerosol_optical_depth' => 'Aerosol Optical Depth',
            'dust' => 'Dust',
            'uv_index' => 'UV Index',
            'uv_index_clear_sky' => 'UV Index Clear Sky',
            'ammonia' => 'Ammonia',
            'alder_pollen' => 'Alder Pollen',
            'birch_pollen' => 'Birch Pollen',
            'grass_pollen' => 'Grass Pollen',
            'mugwort_pollen' => 'Mugwort Pollen',
            'olive_pollen' => 'Olive Pollen',
            'ragweed_pollen' => 'Ragweed Pollen'
        ];
        $this->assertEquals($expectedWeatherVariables, AirQuality::getWeatherVariables());
    }
}
