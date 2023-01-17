<?php


namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\BaseService;

class UserService
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;

    }

    public function getWeatherWithCurrentUserLocation()
    {
        return $this->repo->currentWeather(); //этот метод вызывается из UserRepository
    }
}
