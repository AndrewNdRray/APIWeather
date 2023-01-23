<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


interface BaseInterface
{
    public function currentWeather();
}
