<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Balance;
use App\Services\BalanceService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $user;
    protected $registerController;
    protected $balanceService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BalanceService $balanceService)
    {
        $this->middleware('guest');
        $this->user = new User();
        $this->balanceService = $balanceService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $this->balanceService->create($user->id);

        $this->redirectTo = 'login';
        return $user;
    }

    /**
     * redirect to google login
     */
    public function googleAuth()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * handle google accout register or login
     */
    public function googleAuthCallback()
    {
        $user = Socialite::driver('google')->user();
        $existUser = $this->user->query()->where('email', $user->getEmail())->first();

        if (!$existUser) {
            $data['email'] = $user->getEmail();
            $data['name'] = $user->getName();
            $data['password'] = Hash::make($user->id);
            $existUser = $this->create($data);
        }

        app('auth')->login($existUser);
        return redirect()->route('index');
    }
}
