<?php

use Air\Quality\AirQuality;
use Air\Quality\Tests\TestCase;

class AirQualityNowTest extends TestCase
{
    public function test_getting_air_quality_now_should_return_an_air_quality_response()
    {
        $airQuality = new AirQuality(44.43, 26.11);
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_cams_europe_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_europe');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_cams_global_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setDomain('cams_global');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_cams_europe_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_time_format_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimeFormat('unixtime');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates($response, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_wrong_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $this->expectExceptionObject(new InvalidArgumentException(sprintf('The timezone is not support this wrong_time_zone.')));
        $airQuality->setTimezone('wrong_time_zone');
        $airQuality->getNow();
    }

    /** @depends test_getting_air_quality_now_with_time_format_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_good_timezone_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setTimezone('Europe/Bucharest');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates(
            $response,
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s'),
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s')
        );

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_good_timezone_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_cell_selection_nearest_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('nearest');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates(
            $response,
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s'),
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s')
        );

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_cell_selection_nearest_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_cell_selection_sea_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('sea');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates(
            $response,
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s'),
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s')
        );

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_cell_selection_sea_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_cell_selection_land_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->setCellSelection('land');
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertResponseIsBetweenDates(
            $response,
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s'),
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s')
        );

        return $airQuality;
    }

    /** @depends test_getting_air_quality_now_with_cell_selection_land_should_return_an_air_quality_response */
    public function test_getting_air_quality_now_with_filtered_weather_variables_should_return_an_air_quality_response(AirQuality $airQuality)
    {
        $airQuality->with(['carbon_monoxide']);
        $response = $airQuality->getNow();
        $this->assertIsAirQualityResponseInstance($response);
        $this->assertAirQualityResponsePropertiesAreNotEmpty($response);
        $this->assertEquals(1, count($response->units));
        $this->assertEquals(1, count($response->hourly));
        $this->assertArrayHasKey('carbon_monoxide', $response->units);
        $this->assertArrayHasKey('carbon_monoxide', $response->hourly);
        $this->assertResponseIsBetweenDates(
            $response,
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s'),
            (new DateTime('now', new DateTimeZone('Europe/Bucharest')))->format('Y-m-d H:i:s')
        );

        return $airQuality;
    }
}
