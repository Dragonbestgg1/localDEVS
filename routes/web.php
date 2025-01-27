<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\StudentController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// tasks routes
Route::view('/tasks', 'pages.tasks')->name('tasks');

// competition routes
Route::view('/competition', 'pages.competition')->name('competition');

// submitions routes
Route::view('/submitions', 'pages.submitions')->name('submitions');
Route::get('/student/submissions', [StudentController::class, 'getStudentSubmissions']);
Route::get('/get-unique-classes', [StudentController::class, 'getUniqueClasses']);


// Define other routes for filtering, refetching data, etc.


// leaderboard routes
Route::view('/leaderboard', 'pages.leaderboard')->name('leaderboard');

// code space routes
Route::view('/code_space', 'pages.code_space')->name('code_space');


// google routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';

Route::get('/get-class', function () {
    try {
        $class = Cookie::get('class');
        Log::info('Cookie class:', ['class' => $class]);

        if ($class) {
            $decryptedClass = Crypt::decryptString($class);
            Log::info('Decrypted class:', ['class' => $decryptedClass]);
            return response()->json(['class' => $decryptedClass]);
        }
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Decryption failed: ' . $e->getMessage());
    }
    return response()->json(['class' => null]);
});
