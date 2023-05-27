<?php

namespace Air\Quality;

use Air\Quality\Exception\AirQualityResponseException;
use Air\Quality\Weather\Domain\CellSelection;
use Air\Quality\Weather\Domain\DomainsSource;
use Air\Quality\Weather\Domain\Factory\AirQualityClientFactory;
use Air\Quality\Weather\Domain\Variable;
use Air\Quality\Weather\Infrastructure\AirQualityClient;
use Air\Quality\Weather\Infrastructure\AirQualityResponse;
use DateInterval;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;

final class AirQuality
{
    /** @var string[] */
    private array $weatherVariables = [];

    private readonly AirQualityClient $airQualityClient;

    private DomainsSource $domainsSource = DomainsSource::AUTO;

    private string $timezone = 'GMT';

    private ?DateTime $startDate = null;

    private ?DateTime $endDate = null;

    private int $pastDays = 0;

    private CellSelection $cellSelection = CellSelection::NEAREST;

    private readonly AirQualityClientFactory $airQualityClientFactory;

    public function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        ?callable $handler = null
    ) {
        $this->airQualityClientFactory = new AirQualityClientFactory($handler);
        $this->airQualityClient = $this->airQualityClientFactory->getClient();
        $this->weatherVariables = Variable::values();
    }

    public function setDomain(string $domain): self
    {
        $this->domainsSource = DomainsSource::from($domain);

        return $this;
    }

    public function setTimezone(string $timezone): self
    {
        if (!in_array($timezone, DateTimeZone::listIdentifiers())) {
            throw new InvalidArgumentException(sprintf('The timezone is not support this %s.', $timezone));
        }

        $this->timezone = $timezone;

        return $this;
    }

    public function setCellSelection(string $cellSelection): self
    {
        $this->cellSelection = CellSelection::from($cellSelection);

        return $this;
    }

    /** @param  string[]  $weatherVariables  */
    public function with(array $weatherVariables): self
    {
        $this->weatherVariables = $weatherVariables;

        return $this;
    }

    /**
     * @throws AirQualityResponseException
     */
    public function getNow(): AirQualityResponse
    {
        $weatherVariables = $this->airQualityClient->getAirQuality($this->buildParams());
        $datetime = new DateTime('now', new DateTimeZone($this->timezone));
        $weatherVariables = $weatherVariables->filterByDateTime($datetime);

        return new AirQualityResponse($weatherVariables->getGroupedByDateTime(), $weatherVariables->getUnits());
    }

    public function getBetweenDates(string $startDate, string $endDate): AirQualityResponse
    {
        $this->startDate = new DateTime($startDate, new DateTimeZone($this->timezone));
        $this->endDate = new DateTime($endDate, new DateTimeZone($this->timezone));
        $weatherVariables = $this->airQualityClient->getAirQuality($this->buildParams());
        $weatherVariables = $weatherVariables->filterByDateTimeInterval($this->startDate, $this->endDate);

        return new AirQualityResponse($weatherVariables->getGroupedByDateTime(), $weatherVariables->getUnits());
    }

    public function getPast(int $days = 0): AirQualityResponse
    {
        if ($days < 0) {
            throw new InvalidArgumentException('The number of days in the past should be equal or greater than 0');
        }

        $this->pastDays = $days;
        $startDateTime = new DateTime('today', new DateTimeZone($this->timezone));
        $daysSubstractInterval = DateInterval::createFromDateString(sprintf('%d days', $days));
        if ($daysSubstractInterval instanceof DateInterval) {
            $startDateTime->sub($daysSubstractInterval);
        }

        $endDateTime = new DateTime('now', new DateTimeZone($this->timezone));
        $weatherVariables = $this->airQualityClient->getAirQuality($this->buildParams());
        $weatherVariables = $weatherVariables->filterByDateTimeInterval($startDateTime, $endDateTime);

        return new AirQualityResponse($weatherVariables->getGroupedByDateTime(), $weatherVariables->getUnits());
    }

    /**
     * @return array{'query': array{latitude: float, longitude: float, hourly: string, domains: string, timezone: string, past_days?: int, start_date?: string, end_date?: string, cell_selection?: string}|array{latitude: float, longitude: float, hourly: string, domains: string, timeformat: string, timezone: string, past_days?: int, cell_selection?: string}}
     */
    private function buildParams(): array
    {
        $params = [
            'query' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'hourly' => implode(',', $this->weatherVariables),
                'domains' => $this->domainsSource->value,
                'timezone' => $this->timezone,
                'cell_selection' => $this->cellSelection->value,
            ],
        ];

        if (!empty($this->pastDays)) {
            $params['query']['past_days'] = $this->pastDays;
        }

        if ($this->startDate instanceof \DateTime && $this->endDate instanceof \DateTime) {
            $params['query']['start_date'] = $this->startDate->format('Y-m-d');
            $params['query']['end_date'] = $this->endDate->format('Y-m-d');
        }

        return $params;
    }
}
