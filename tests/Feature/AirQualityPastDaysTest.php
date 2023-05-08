<?php

namespace Air\Quality\Tests\Feature;

use Air\Quality\AirQuality;
use Air\Quality\Tests\TestCase;
use InvalidArgumentException;

class AirQualityPastDaysTest extends TestCase
{
    public function test_getting_air_quality_past_0_days_with_negative_days_would_return_exception()
    {
        $this->expectExceptionObject(new InvalidArgumentException('The number of days in the past should be equal or greater than 0'));
        $airQuality = new AirQuality(44.43, 26.11);
        $airQuality->getPast(-1);
    }

    public function test_getting_air_quality_past_0_days_should_return_an_air_quality_response_from_beginning_of_the_day_until_now()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_should_return_an_air_quality_response_from_beginning_of_the_day_until_now */
    public function test_getting_air_quality_past_0_days_with_cams_europe_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_europe');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_cams_global_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_global');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_cams_global_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_time_format_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimeFormat('unixtime');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_wrong_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('The timezone is not support this wrong_time_zone.')));
        $airQuality->setTimezone('wrong_time_zone');
        $airQuality->getPast();
    }

    /** @depends test_getting_air_quality_past_0_days_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_good_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimezone('Europe/Bucharest');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_good_timezone_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_cell_selection_nearest_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('nearest');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_cell_selection_nearest_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_cell_selection_sea_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('sea');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_cell_selection_sea_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_cell_selection_land_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('land');
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_0_days_with_cell_selection_land_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_0_days_with_filtered_weather_variables_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->with(['carbon_monoxide']);
        $response = $airQuality->getPast();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 0, 'Europe/Bucharest');
        $this->assertEquals(1, count($response->units));
        $this->assertArrayHasKey('carbon_monoxide', $response->units);
        foreach ($response->hourly as $values) {
            $this->assertEquals(1, count($values));
            $this->assertArrayHasKey('carbon_monoxide', $values);
        }

        return $airQuality;
    }

    public function test_getting_air_quality_past_1_days_should_return_an_air_quality_response_from_beginning_of_the_day_until_now()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_should_return_an_air_quality_response_from_beginning_of_the_day_until_now */
    public function test_getting_air_quality_past_1_days_with_cams_europe_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_europe');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_cams_global_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_global');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_cams_global_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_time_format_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimeFormat('unixtime');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'GMT');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_wrong_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('The timezone is not support this wrong_time_zone.')));
        $airQuality->setTimezone('wrong_time_zone');
        $airQuality->getPast(1);
    }

    /** @depends test_getting_air_quality_past_1_days_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_good_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimezone('Europe/Bucharest');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_good_timezone_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_cell_selection_nearest_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('nearest');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_cell_selection_nearest_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_cell_selection_sea_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('sea');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_cell_selection_sea_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_cell_selection_land_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('land');
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'Europe/Bucharest');

        return $airQuality;
    }

    /** @depends test_getting_air_quality_past_1_days_with_cell_selection_land_should_return_an_air_quality_response */
    public function test_getting_air_quality_past_1_days_with_filtered_weather_variables_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->with(['carbon_monoxide']);
        $response = $airQuality->getPast(1);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsFromPastDays($response, 1, 'Europe/Bucharest');
        $this->assertEquals(1, count($response->units));
        $this->assertArrayHasKey('carbon_monoxide', $response->units);
        foreach ($response->hourly as $values) {
            $this->assertEquals(1, count($values));
            $this->assertArrayHasKey('carbon_monoxide', $values);
        }

        return $airQuality;
    }
}
