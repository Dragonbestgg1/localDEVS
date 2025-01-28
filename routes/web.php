<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\JsonExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\StudentController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompetitionController;

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

//news routes
Route::post('/news/store', [NewsController::class, 'store'])->name('news.store');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// tasks routes
Route::view('/tasks', 'pages.tasks.tasks')->name('tasks');
Route::get('/tasks/all', [TaskController::class, 'index']);

Route::view('/tasks/mysubmissions', 'pages.tasks.tasksSub')->name('tasksSub');


Route::view('/tasks/addTask', 'pages.tasks.tasksAdd')->name('tasksAdd');
Route::post('/submit-task', [TaskController::class, 'submitTask']);

// competition routes
Route::view('/competition', 'pages.competition.competitions')->name('competition');
Route::get('/competitions/all', [CompetitionController::class, 'index']);


Route::view('/competition/submitionsComp', 'pages.competition.submitions')->name('submitionsComp');

Route::view('/competition/addCompetition', 'pages.competition.addCompetition')->name('addCompetition');
Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');


Route::view('/competition/results', 'pages.competition.results')->name('results');

// submitions routes
Route::view('/submitions', 'pages.submitions')->name('submitions');
Route::get('/student/submissions', [StudentController::class, 'getStudentSubmissions']);
Route::get('/get-unique-classes', [StudentController::class, 'getUniqueClasses']);
Route::get('/export-classes', [JsonExportController::class, 'exportClassesToJson']);

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

Route::get('/get-author', function () {
    try {
        $name = Cookie::get('name');
        $surname = Cookie::get('surname');
        Log::info('Cookie name:', ['name' => $name]);
        Log::info('Cookie surname:', ['surname' => $surname]);

        if ($name && $surname) {
            $decryptedName = Crypt::decryptString($name);
            $decryptedSurname = Crypt::decryptString($surname);
            Log::info('Decrypted name:', ['name' => $decryptedName]);
            Log::info('Decrypted surname:', ['surname' => $decryptedSurname]);
            return response()->json(['name' => $decryptedName, 'surname' => $decryptedSurname]);
        }
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Decryption failed: ' . $e->getMessage());
    }
    return response()->json(['name' => null, 'surname' => null]);
});
