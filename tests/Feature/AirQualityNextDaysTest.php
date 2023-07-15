<?php

namespace Air\Quality\Tests\Feature;

use Air\Quality\AirQuality;
use Air\Quality\Tests\TestCase;
use InvalidArgumentException;

class AirQualityNextDaysTest extends TestCase
{
    public function test_getting_air_quality_next_days_with_negative_days_would_return_exception()
    {
        $this->expectExceptionObject(new InvalidArgumentException('The number of days in the future should be equal or greater than 0'));
        $airQuality = new AirQuality(44.43, 26.11);
        $airQuality->getNext(-1);
    }

    public function test_getting_air_quality_next_1_day_should_return_an_air_quality_response_from_beginning_of_the_day_until_now()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_day_should_return_an_air_quality_response_from_beginning_of_the_day_until_now */
    public function test_getting_air_quality_next_1_days_with_cams_europe_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_europe');
        $response = $airQuality->getNext(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_days_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_wrong_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('The timezone is not support this wrong_time_zone.')));
        $airQuality->setTimezone('wrong_time_zone');
        $airQuality->getNext(1);
    }

    /** @depends test_getting_air_quality_next_1_days_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_good_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimezone('Europe/Bucharest');
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_days_with_good_timezone_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_cell_selection_nearest_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('nearest');
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_days_with_cell_selection_nearest_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_cell_selection_sea_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('sea');
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_days_with_cell_selection_sea_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_cell_selection_land_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('land');
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_next_1_days_with_cell_selection_land_should_return_an_air_quality_response */
    public function test_getting_air_quality_next_1_days_with_filtered_weather_variables_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->with(['carbon_monoxide']);
        $response = $airQuality->getNext();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromNextDays($response, 1, 'Europe/Bucharest');
        $this->assertEquals(1, count($response->units));
        $this->assertArrayHasKey('Carbon Monoxide', $response->units);
        foreach ($response->hourly as $values) {
            $this->assertEquals(1, count($values));
            $this->assertArrayHasKey('Carbon Monoxide', $values);
        }

        return $airQuality;
    }
}
