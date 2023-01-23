<?php


namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseService
{
    /**
     * Repository
     *
     * @var Repository
     */
    public $repo;

    public function getWeatherWithCurrentUserLocation()
    {
        $this->repo->currentWeather();
    }

}
