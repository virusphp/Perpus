<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserShoulVerified
{
    /**
     * Handle an incoming request.
     *`
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (Auth::check() && !Auth::user()->is_verified) {
            $link = url('auth/send-verification').'?email='.urlencode(Auth::user()->email);
            Auth::logout();
            Session::flash('flash_notification', [
                'level'=> 'warning',
                'message' => "Akun anda belum aktif. silahkan klik pada link aktifasi yang telah kami kirim. <a href='$link' class='alert-link'>Kirim Lagi</a>"
            ]);
            return redirect('/login');
        }
        return $response;

    }
}
