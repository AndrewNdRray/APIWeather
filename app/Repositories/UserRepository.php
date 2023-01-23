<?php


namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function currentWeather()
    {
        $ip = Auth::user()->IP;
        $api_1 = 'https://ipapi.co/' . $ip . '/latlong/';
        $location = file_get_contents($api_1);
        $point = explode(",", $location);

        # Part 2 (get weather forecast)
        $api_2 = 'http://api.openweathermap.org/data/2.5/weather?lat=' . $point[0] . '&lon=' . $point[1] . '&appid=d72ea127b2eb53d908dba758402f87d6';
        $weather = file_get_contents($api_2);
        $weatherApi = array($weather);
        $weather = str_replace('"', '', $weather);
        $weatherApi = explode(',', $weather);
        $redis = Redis::connection();
        $cache = Cache::put('user_details', $weatherApi, Carbon::now()->addMinutes(1));

        $redis->set('user_details', json_encode([$weatherApi]));
        $redis = Redis::connection();
        $response = $redis->get('user_details');
        $value = Cache::has('user_details');
        if (!empty($cache))
        {
            return json_decode($response);
        }else{
            $response = $redis->get('user_details');
        }
        //dd($value);

        $responseRedis = json_decode($response);
        //dd($responseRedis);
        return view('weather')->with(['weather' => $responseRedis]);
    }

}

