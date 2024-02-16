<?php

namespace IlhamriSKY\GetTimeZoneByCity;

use Carbon\Carbon;

/**
 * Main class.
 *
 * @author Ilham Riski Wibowo <ilhamriskiwibowo@gmail.com>
 * @version 1.0.0
 */
class GetTimeZoneByCity
{
    private const CITIES_FILE_PATH = __DIR__ . '/Data/cities.json';
    private array $cities;

    /**
     * Constructor to load cities data from a JSON file.
     */
    public function __construct()
    {
        $this->loadCitiesData();
    }

    private function loadCitiesData()
    {
        if (!file_exists(self::CITIES_FILE_PATH)) {
            throw new \RuntimeException('Cities data file not found.');
        }

        $jsonContent = file_get_contents(self::CITIES_FILE_PATH);
        $this->cities = json_decode($jsonContent, true);
    }

    /**
     * Check if a given city exists in the dataset.
     *
     * @param string $city The name of the city.
     * @return bool Whether the city exists in the dataset.
     */
    public function cityExists(string $city): bool
    {
        $matchingCity = array_filter($this->cities, fn($c) => strtolower($c['name']) === strtolower($city));

        return count($matchingCity) > 0;
    }

    /**
     * Get a list of all cities available in the dataset.
     *
     * @return array List of all cities.
     */
    public function getAllCities(): array
    {
        return array_column($this->cities, 'name');
    }


    /**
     * Get all data for a specific city.
     *
     * @param string $city The name of the city.
     * @return array|null All data for the city, or null if not found.
     */
    public function getAllData(string $city): ?array
    {
        $matchingCity = array_filter($this->cities, fn($c) => strtolower($c['name']) === strtolower($city));

        return $matchingCity ? reset($matchingCity) : null;
    }

    /**
     * Get the timezone for a specific city.
     *
     * @param string $city The name of the city.
     * @return string|null The timezone of the city, or null if not found.
     */
    public function getTimeZone(string $city): ?string
    {
        $matchingCity = array_filter($this->cities, fn($c) => strtolower($c['name']) === strtolower($city));

        return $matchingCity ? reset($matchingCity)['timezone'] : null;
    }

    /**
     * Get the UTC offset for a specific city.
     *
     * @param string $city The name of the city.
     * @return string|null The UTC offset of the city, or null if not found.
     */
    public function getTimeUTC(string $city): ?string
    {
        $matchingCity = array_filter($this->cities, fn($c) => strtolower($c['name']) === strtolower($city));

        return $matchingCity ? reset($matchingCity)['utc'] : null;
    }

    /**
     * Get the latitude and longitude for a specific city.
     *
     * @param string $city The name of the city.
     * @return array|null Latitude and longitude for the city, or null if not found.
     */
    public function getTimeLatLong(string $city): ?array
    {
        $matchingCity = array_filter($this->cities, fn($c) => strtolower($c['name']) === strtolower($city));

        return $matchingCity ? ['lat' => reset($matchingCity)['lat'], 'lng' => reset($matchingCity)['lng']] : null;
    }

    /**
     * Get a list of cities based on their country code.
     *
     * @param string $countryCode The country code (e.g., "AD").
     * @return array List of cities in the specified country.
     */
    public function getCitiesByCountry(string $countryCode): array
    {
        $filteredCities = array_filter($this->cities, fn($city) => strtoupper($city['country']) === strtoupper($countryCode));
        return array_column($filteredCities, 'name');
    }


    /**
     * Get the current time in a specified city's timezone.
     *
     * @param string $city The name of the city.
     * @return Carbon|null The current time in the city's timezone, or null if the city is not found.
     */
    public function getCurrentTimeInCity(string $city): ?Carbon
    {
        $cityTimeZone = $this->getTimeZone($city);

        return $cityTimeZone ? Carbon::now($cityTimeZone) : null;
    }

    /**
     * Get the city based on latitude and longitude coordinates.
     *
     * @param float $latitude The latitude of the location.
     * @param float $longitude The longitude of the location.
     * @param float $tolerance The allowed tolerance for coordinate matching (optional, default is 0.1).
     * @return array|null The city data, or null if no matching city is found.
     */
    public function getCityByCoordinates(float $latitude, float $longitude, float $tolerance = 0.1): ?array
    {
        $matchingCity = array_filter($this->cities, function ($city) use ($latitude, $longitude, $tolerance) {
            $latDiff = abs($city['lat'] - $latitude);
            $lngDiff = abs($city['lng'] - $longitude);
            return $latDiff <= $tolerance && $lngDiff <= $tolerance;
        });

        return $matchingCity ? reset($matchingCity) : null;
    }

    /**
     * Convert time from the source city's timezone to the destination city's timezone.
     *
     * @param string $sourceCity The name of the source city.
     * @param string $destinationCity The name of the destination city.
     * @param string $format The format of the output time (optional, default is 'Y-m-d H:i:s').
     * @return string|null The converted time in the destination city's timezone, or null if cities are not found.
     */
    public function convertTimeBetweenCities(string $sourceCity, string $destinationCity, string $format = 'Y-m-d H:i:s'): ?string
    {
        $sourceTimeZone = $this->getTimeZone($sourceCity);
        $destinationTimeZone = $this->getTimeZone($destinationCity);

        if ($sourceTimeZone && $destinationTimeZone) {
            $sourceTime = Carbon::now($sourceTimeZone);
            $destinationTime = $sourceTime->copy()->setTimezone($destinationTimeZone);

            return $destinationTime->format($format);
        }

        return null;
    }

    /**
     * Compare the current time with the time in a specific city.
     *
     * @param string $city The name of the city.
     * @return array|null The time difference or null if the city is not found.
     */
    public function compareLocalTimeWithCityTime(string $city): ?array
    {
        $cityTimeZone = $this->getTimeZone($city);

        if ($cityTimeZone) {
            $localTime = Carbon::now();
            $cityTime = Carbon::now($cityTimeZone);

            $diff = $localTime->diff($cityTime);

            return [
                'local_timezone' => $localTime->getTimezone()->getName(),
                'local_datetime' => $localTime->toDateTimeString(),
                'city_timezone' => $cityTimeZone,
                'city_datetime' => $cityTime->toDateTimeString(),
                'time_difference' => [
                    'hours' => $diff->h,
                    'minutes' => $diff->i,
                    'seconds' => $diff->s,
                ],
            ];
        }

        return null;
    }
}
