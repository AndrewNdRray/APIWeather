<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseInterface;

abstract class BaseRepository implements BaseInterface
{

    protected $model;


    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function currentWeather()
    {
        return $this->model;
    }

}
