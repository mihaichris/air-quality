<?php

namespace Air\Quality\Tests\Feature;

use Air\Quality\AirQuality;
use Air\Quality\Tests\TestCase;
use InvalidArgumentException;

class AirQualityBetweenDaysTest extends TestCase
{
    private string $startDateTime;

    private string $endDateTime;

    public function setUp(): void
    {
        $this->startDateTime = date('Y-m-d H:i:s');
        $this->endDateTime = date('Y-m-d H:i:s', strtotime('+1 days'));
    }

    public function test_getting_air_quality_between_should_return_an_air_quality_response()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    public function test_getting_air_quality_between_reversed_dates_should_return_an_empty_air_quality_response()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getBetweenDates($this->endDateTime, $this->startDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreEmpty($response);
    }

    /** @depends test_getting_air_quality_between_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_cams_europe_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_europe');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_cams_global_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_global');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_time_format_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimeFormat('unixtime');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_wrong_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('The timezone is not support this wrong_time_zone.')));
        $airQuality->setTimezone('wrong_time_zone');
        $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
    }

    /** @depends test_getting_air_quality_between_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_good_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimezone('Europe/Bucharest');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_good_timezone_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_cell_selection_nearest_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('nearest');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_cell_selection_nearest_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_cell_selection_sea_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('sea');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_cell_selection_sea_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_with_cell_selection_land_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('land');
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);

        return $airQuality;
    }

    /** @depends test_getting_air_quality_between_with_cell_selection_land_should_return_an_air_quality_response */
    public function test_getting_air_quality_between_dates_with_filtered_weather_variables_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->with(['carbon_monoxide']);
        $response = $airQuality->getBetweenDates($this->startDateTime, $this->endDateTime);
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, $this->startDateTime, $this->endDateTime);
        $this->assertEquals(1, count($response->units));
        $this->assertArrayHasKey('carbon_monoxide', $response->units);
        foreach ($response->hourly as $values) {
            $this->assertEquals(1, count($values));
            $this->assertArrayHasKey('carbon_monoxide', $values);
        }

        return $airQuality;
    }
}
