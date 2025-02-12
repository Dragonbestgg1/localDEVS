<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $state = bin2hex(random_bytes(16));
        $redirectUrl = Socialite::driver('google')->stateless()->with(['state' => $state])->redirect()->getTargetUrl();
        // Log::info('Redirecting to Google with state: ' . $state);
        return redirect($redirectUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            // Log::info('User returned from Google: ', ['user' => $user]);

            $findUser = User::where('email', $user->email)->first();
            $email = $user->email;
            // Log::info('User email: ' . $email);

            // Extract the class part from the email with validation
            $class = explode('.', $email)[0];
            // Log::info('Extracted class from email: ' . $class);

            // Validate the class part of the email
            if (!preg_match('/^[A-Za-z]{2,3}[0-9]{2}$/', $class)) {
                $class = 'teacher';
            }

            if (preg_match('/^[A-Za-z]{2,3}[0-9]{2}$/', $class)) {
                $class = 'teacher';
            }

            $name = $user->user['given_name'];
            $surname = $user->user['family_name'];

            if ($findUser) {
                Auth::login($findUser);
                $token = $findUser->createToken('auth_token')->plainTextToken;
                $userDetails = [
                    'id' => $findUser->id,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'class' => $class
                ];
                // Log::info('Existing user logged in: ', ['userDetails' => $userDetails]);
            } else {
                $newUser = User::create([
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'google_id' => $user->id,
                    'password' => encrypt('my-google'),
                    'class' => $class
                ]);
                Auth::login($newUser);
                $token = $newUser->createToken('auth_token')->plainTextToken;
                $userDetails = [
                    'id' => $newUser->id,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'class' => $class
                ];
                // Log::info('New user created and logged in: ', ['userDetails' => $userDetails]);
            }

            $cookies = [
                'token' => $token,
                'id' => $userDetails['id'],
                'name' => $userDetails['name'],
                'surname' => $userDetails['surname'],
                'email' => $userDetails['email'],
                'class' => $userDetails['class']
            ];

            // Log::info('Setting query parameters in cookies: ', $cookies);

            foreach ($cookies as $key => $value) {
                $encryptedValue = Crypt::encryptString($value);
                Cookie::queue(Cookie::make($key, $encryptedValue, 180, '/', null, false, false));
            }
            

            $redirectUrl = 'http://127.0.0.1:8000/dashboard';
            // Log::info('Final redirect URL: ' . $redirectUrl);

            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            // Log::error('Authentication with Google failed: ' . $e->getMessage());
            return response()->json(['message' => 'Authentication with Google failed', 'error' => $e->getMessage()], 500);
        }
    }
}
