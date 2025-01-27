<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Rules\AllowedEmail;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class, new AllowedEmail('@vtdt.edu')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = $request->email;
        $class = explode('.', $email)[0];

        // Validate the class part of the email
        if (!preg_match('/^[A-Za-z]{2,3}[0-9]{2}$/', $class)) {
            $class = 'teacher';
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'class' => $class,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        $remember = $request->has('remember');
        $cookieExpiration = $remember ? 0 : 20;
        $cookies = [
            cookie('name', $request->name, $cookieExpiration),
            cookie('surname', $request->surname, $cookieExpiration),
            cookie('class', $class, $cookieExpiration),
            cookie('email', $request->email, $cookieExpiration),
            cookie('user_id', $user->id, $cookieExpiration),
        ];

        return redirect(route('dashboard', absolute: false))->withCookies($cookies);
    }
}
