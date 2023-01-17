<?php


namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function currentWeather()
    {
        return $this->model->getWeather();
    }
}
