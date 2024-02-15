# Laravel Timezone by City

## Introduction
Laravel Get Timezone by City package provides a simple way to retrieve timezone information for cities around the world. It utilizes Carbon for date and time manipulation. This README will guide you on how to install and use the package in your Laravel project.

## Data Source
The city data provided by this package is sourced from the GeoNames Gazetteer, available at [GeoNames Gazetteer](https://www.geonames.org/). GeoNames is a geographical database that covers all countries and contains over eleven million place names. As of the last update on February 16, 2024, it provides information for 146,892 cities worldwide.


## Installation
Require this package with composer using the following command:
```bash
composer require ilhamrisky/laravel-timezone-by-city
```
## Usage
Import the namespace and instantiate the class in your Laravel controller or service
```php
use IlhamriSKY\GetTimeZoneByCity\GetTimeZoneByCity;

$timeZoneByCity = new GetTimeZoneByCity();
```

## Example
### Example 1: Check if a city exists in the dataset
```php
$cityToCheck = 'New York City';
$cityExists = $timeZoneByCity->cityExists($cityToCheck);
// $cityExists will be a boolean indicating whether 'New York City' exists in the dataset
echo "Does $cityToCheck exist? " . ($cityExists ? 'Yes' : 'No');
```

### Example 2: Get a list of all cities available in the dataset
```php
$allCities = $timeZoneByCity->getAllCities();
// $allCities will be an array containing names of all cities
echo "All Cities: " . implode(', ', $allCities);
```

### Example 3: Get all data for a specific city
```php
$cityDetails = $timeZoneByCity->getAllData('London');
// $cityDetails will be an array containing details for 'London'
echo "Details for London: " . json_encode($cityDetails);
```

### Example 4: Get the timezone for a specific city
```php
$cityTimeZone = $timeZoneByCity->getTimeZone('Tokyo');
// $cityTimeZone will be a string containing the timezone for 'Tokyo'
echo "Timezone for Tokyo: " . $cityTimeZone;
```

### Example 5: Get the UTC offset for a specific city
```php
$cityUtcOffset = $timeZoneByCity->getTimeUTC('Sydney');
// $cityUtcOffset will be a string containing the UTC offset for 'Sydney'
echo "UTC Offset for Sydney: " . $cityUtcOffset;
```

### Example 6: Get the latitude and longitude for a specific city
```php
$cityLatLong = $timeZoneByCity->getTimeLatLong('Paris');
// $cityLatLong will be an array containing 'lat' and 'lng' for 'Paris'
echo "Latitude and Longitude for Paris: " . json_encode($cityLatLong);
```

### Example 7: Get a list of cities based on their country code
```php
$countryCode = 'US';
$citiesInCountry = $timeZoneByCity->getCitiesByCountry($countryCode);
// $citiesInCountry will be an array containing names of cities in the United States
echo "Cities in $countryCode: " . implode(', ', $citiesInCountry);
```

### Example 8: Get the current time in a specified city's timezone
```php
$currentTimeInCity = $timeZoneByCity->getCurrentTimeInCity('Berlin');
// $currentTimeInCity will be a Carbon instance representing the current time in Berlin's timezone
echo "Current Time in Berlin: " . ($currentTimeInCity ? $currentTimeInCity->toDateTimeString() : 'City not found');
```

### Example 9: Get the city based on latitude and longitude coordinates
```php
$latitude = 40.7128;
$longitude = -74.0060;
$matchingCity = $timeZoneByCity->getCityByCoordinates($latitude, $longitude);
// $matchingCity will be an array containing details for the city matching the coordinates
echo "City at ($latitude, $longitude): " . json_encode($matchingCity);
```

### Example 10: Convert time between two cities' timezones
```php
$sourceCity = 'Los Angeles';
$destinationCity = 'London';
$convertedTime = $timeZoneByCity->convertTimeBetweenCities($sourceCity, $destinationCity, 'Y-m-d H:i:s');
// $convertedTime will be a string containing the converted time in London's timezone
echo "Converted Time from $sourceCity to $destinationCity: " . ($convertedTime ?? 'Cities not found');
```

### Example 11: Compare local time and city time
```php
$city = 'Semarang';
$compareTime = $timeZoneByCity->compareLocalTimeWithCityTime($city);

// $compareTime will be a array containing the compare local time and city time
echo "Time in {$city}: {$compareTime['city_datetime']}\n";
echo "Time in local timezone: {$compareTime['local_datetime']}\n";
echo "Time difference: {$compareTime['time_difference']['hours']} hours, {$compareTime['time_difference']['minutes']} minutes, {$compareTime['time_difference']['seconds']} seconds\n";
```

## License (MIT License):
This package is released under the MIT License, which allows you to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the software. The only condition is that the above copyright notice and this permission notice shall be included in all copies or substantial portions of the software.

Feel free to contribute, create issues, or submit pull requests to enhance the functionality or fix any bugs.