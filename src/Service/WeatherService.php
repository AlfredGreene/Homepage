<?php

namespace App\Service;

use App\Entity\WeatherForecastList;
use App\Entity\WeatherForecast;

class WeatherService
{
    const WEATHER_CACHE_DURATION = 0;
    const API_URL_PREFIX = 'http://api.openweathermap.org/data/2.5/';

    /**
     * @var array
     */
    protected $types = [
        WeatherForecast::TYPE_RAIN,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_CLOUD,
        WeatherForecast::TYPE_PARTLY_CLOUD,
        WeatherForecast::TYPE_SNOW,
        WeatherForecast::TYPE_SUN,
        WeatherForecast::TYPE_THUNDER
    ];

    /**
     * @var string
     */
    protected $weatherUrl;

    /**
     * @var string
     */
    protected $forecastUrl;

    public function __construct()
    {
        $key = $_SERVER['OPEN_WEATHER_MAP_KEY'];
        $location = $_SERVER['OPEN_WEATHER_MAP_LOCATION'];
        $this->weatherUrl = self::API_URL_PREFIX . 'weather?q=' . $location . '&APPID=' . $key . '&units=metric';
        $this->forecastUrl = self::API_URL_PREFIX . 'forecast/daily/?q=' . $location . '&APPID=' . $key . '&units=metric';
    }

    /**
     * @return WeatherForecastList|null
     * @throws \Exception
     */
    public function fetchForecast()
    {
        if ($_SERVER['APP_ENV'] === 'dev') {
            return $this->dummyForecast();
        }

        $forecastData = $this->downloadForecast();
        return $this->createForecastList($forecastData['weather'], $forecastData['forecast']);
    }

    /**
     * @return WeatherForecastList
     */
    public function dummyForecast()
    {
        $weatherForecastList = new WeatherForecastList();
        for ($i = 0; $i <= 5; $i++) {
            $forecastItem = new WeatherForecast();
            $forecastItem->setTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setMaxTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setMinTemperature(mt_rand(5, 420) / 10);
            $forecastItem->setType($this->types[array_rand($this->types)]);
            $forecastItem->setDescription('Dummy description');

            if ($i === 0) {
                $weatherForecastList->setCurrent($forecastItem);
            } else {
                $weatherForecastList->addUpcoming($forecastItem);
            }
        }

        return $weatherForecastList;
    }

    /**
     * @return WeatherForecastList|null
     * @throws \Exception
     */
    public function getForecastList()
    {
        return $this->fetchForecast();
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function downloadForecast()
    {
        $weather = json_decode(@file_get_contents($this->weatherUrl), true);
        $forecast = json_decode(@file_get_contents($this->forecastUrl), true);

        if (!is_array($weather) || !is_array($forecast)) {
            throw new \Exception('Weatherdata could not be updated. Failed to load API');
        }

        return [
            'weather' => $weather,
            'forecast' => $forecast
        ];
    }

    /**
     * @param $weather
     * @param $forecast
     *
     * @return WeatherForecastList
     */
    protected function createForecastList($weather, $forecast)
    {
        $weatherForecastList = new WeatherForecastList();
        $weatherForecastList->setCurrent($this->mapForecast($weather));

        $forecastList = array_slice($forecast['list'], 0, 5);

        foreach ($forecastList as $item) {
            $weatherForecastList->addUpcoming($this->mapForecast($item, 'week'));
        }
        return $weatherForecastList;
    }

    /**
     * @param array $forecastArray
     *
     * @return WeatherForecast
     */
    protected function mapForecast(array $forecastArray, $type = 'today')
    {
        $weatherForecast = new WeatherForecast();
        $weatherForecast->setTemperature(($type === 'today' ? $forecastArray['main']['temp'] : $forecastArray['temp']['day']));
        $weatherForecast->setMaxTemperature(($type === 'today' ? $forecastArray['main']['temp_max'] : $forecastArray['temp']['max']));
        $weatherForecast->setMinTemperature(($type === 'today' ? $forecastArray['main']['temp_min'] : $forecastArray['temp']['min']));
        $weatherForecast->setType($this->convertType($forecastArray['weather'][0]['main']));
        $weatherForecast->setDescription($forecastArray['weather'][0]['description']);
        return $weatherForecast;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function convertType($type)
    {
        $map = [
            'rain' => WeatherForecast::TYPE_RAIN,
            'clouds' => WeatherForecast::TYPE_CLOUD,
            'mist' => WeatherForecast::TYPE_CLOUD,
            'haze' => WeatherForecast::TYPE_CLOUD,
            'drizzle' => WeatherForecast::TYPE_PARTLY_CLOUD,
            'snow' => WeatherForecast::TYPE_SNOW,
            'clear' => WeatherForecast::TYPE_SUN,
            'thunderstorm' => WeatherForecast::TYPE_THUNDER
        ];

        foreach ($map as $key => $value) {
            if (stripos($type, $key) !== false) {
                return $value;
            }
        }

        return WeatherForecast::TYPE_UNKNOWN;
    }
}
