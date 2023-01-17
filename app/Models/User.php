<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getWeather()
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
        };
        //dd($value);

        $responseRedis = json_decode($response);
        dd($responseRedis);
        return view('weather')->with(['weather' => $responseRedis]);
    }
}
