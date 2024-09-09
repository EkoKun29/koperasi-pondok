<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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

    public function posts(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'email' => 'email|exists:users,email',
                    'password' => 'required',
                ]
            );
    
            $attempts = [
                'email' => $request->email,
                'password' => $request->password,
            ];
    
            if (Auth::attempt($attempts)) {
                return redirect()->intended('home');
            } else {
                return redirect('/')->with('error', 'Maaf email atau password yang anda masukkan salah, Silahkan login kembali!');
            }
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan saat login. Silahkan coba lagi nanti!');
        }
    }
    
    
        public function logout()
        {
            Session::flush();
            Auth::logout();
            return redirect('/');
        }

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
        $this->middleware('auth')->only('logout');
    }
}
