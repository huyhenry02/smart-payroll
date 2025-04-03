<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin(): View|Factory|Application
    {
        return view('page.auth.login');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            if (auth()->attempt($credentials)) {
                return redirect()->route('system.showIndexUser')->with('success', 'Đăng nhập thành công');
            }
            return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
        } catch (Exception $e) {
            return redirect()->route('auth.showLogin')->with('error', $e->getMessage());
        }
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();
        return redirect()->route('auth.showLogin');
    }
}
