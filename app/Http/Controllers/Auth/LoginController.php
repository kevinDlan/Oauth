<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function registerOrLoginUser($data)
    {
        $user = User::where('email', $data->email)->first();

        if(!$user)
        {
            $user = User::create(
                [
                    'name' => $data->name,
                    'email' => $data->email,
                    'provider_id' => $data->id,
                    'avatar' => $data->avatar,
                    'email_verified_at' => Carbon::now()
                ]
            );
        }

        Auth::login($user);
    }

    //Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    //Google callback  
    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->stateless()->user();

        $this->registerOrLoginUser($user);
        return redirect()->route('home');
    }

    //Facebook Login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    //facebook callback  
    public function handleFacebookCallback()
    {

        $user = Socialite::driver('facebook')->stateless()->user();

        $this->registerOrLoginUser($user);
        return redirect()->route('home');
    }

    //Github Login
    public function redirectToGithub()
    {
        return Socialite::driver('github')->stateless()->redirect();
    }

    //github callback  
    public function handleGithubCallback()
    {

        $user = Socialite::driver('github')->stateless()->user();

        $this->registerOrLoginUser($user);
        return redirect()->route('home');
    }

}
