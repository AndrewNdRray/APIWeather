<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use function Illuminate\Session\userId;
use Illuminate\Support\Facades\Redis;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /* public function __construct()
     {
         $this->middleware('auth');
     }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
            $isUser = User::where('google_id', $user->id)->first();

            // если такой пользователь есть, значит авторизуемся
            //иначе регистрируем

            if ($isUser) {
                Auth::login($isUser);
                return redirect('/login');
            } else {
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('user'),
                ]);

                Auth::login($createUser);

                return redirect('/login');
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function getWeather(Request $request)
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

        $redis->set('user_details', json_encode([
                $weatherApi
            ])
        );
        $redis = Redis::connection();
        $response = $redis->get('user_details');

        $responseRedis = json_decode($response);
        dd($responseRedis);
        return view('weather')->with(['weather' => $responseRedis]);


    }

}
