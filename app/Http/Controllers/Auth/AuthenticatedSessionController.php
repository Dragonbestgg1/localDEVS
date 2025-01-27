<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Rules\AllowedEmail;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', new AllowedEmail('@vtdt.edu')],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Authenticate the user
        if (! Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        $remember = $request->has('remember');
        $cookieExpiration = $remember ? 60 : 0;

        $user = Auth::user();

        $cookies = [
            cookie('class', $user->class, $cookieExpiration),
            cookie('email', $user->email, $cookieExpiration),
            cookie('user_id', $user->id, $cookieExpiration),
            cookie('name', $user->name, $cookieExpiration),
            cookie('surname', $user->surname, $cookieExpiration),
            cookie('token', $user->token, $cookieExpiration),
        ];

        return redirect()->intended(route('dashboard', absolute: false))->withCookies($cookies);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Remove all cookies
        foreach ($request->cookies as $cookieName => $cookieValue) {
            Cookie::queue(Cookie::forget($cookieName));
        }

        // Redirect to the homepage or login page
        return redirect('/');
    }
}
