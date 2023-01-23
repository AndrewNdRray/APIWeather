<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Services\UserService;


class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $users;


    /**
     * Create a new controller instance.
     *
     * @param UserService $users
     */
    public function __construct(UserService $users)
    {
        $this->users = $users;
    }
    public function index (Request $request)
    {
        return view('index');
    }

    /**
     * Show the application dashboard.
     * 59+
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function currentWeather()
    {
        return $this->users->getWeatherWithCurrentUserLocation();
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

}
