<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        } elseif ($user->isProfesseur()) {
            return redirect()->route('emargements.index');
        }
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
} 