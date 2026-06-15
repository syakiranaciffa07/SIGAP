<?php

namespace App\Services;

class WeatherService
{
    protected string $currentCondition;

    protected array $conditions = [
        'Cerah' => 20,
        'Hujan Ringan' => 40,
        'Hujan Sedang' => 70,
        'Hujan Lebat' => 100,
    ];

    public function __construct()
    {
        $keys = array_keys($this->conditions);
        $this->currentCondition = $keys[array_rand($keys)];
    }

    public function getWeatherCondition(): string
    {
        return $this->currentCondition;
    }

    public function getWeatherScore(): float
    {
        return (float) ($this->conditions[$this->currentCondition] ?? 20);
    }
}
