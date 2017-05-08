<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
Use Session;
Use Auth;
Use Illuminate\Http\Request;
use App\User;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('user-should-verified');
    }

    public function verify(Request $request, $token)
    {
        $email = $request->get('email');
        $user = User::where('verification_token', $token)->where('email', $email)->first();
        if ($user) {
            $user->verify();
            Session::flash('flash_notification', [
                'level' => 'success',
                'message' => 'Behasil melalukan verifikasi'
            ]);
            Auth::login($user);
        }
        return redirect('/');
    }

    public function sendVerification(Request $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        if ($user && !$user->is_verified) {
            $user->sendVerification();
            Session::flash('flash_notification', [
                'level'=> 'success',
                'message' => 'Silahkan klik pada link aktivasi yang telah kita kirim.'
            ]);
        }

        return redirect('/login');
    }
}
