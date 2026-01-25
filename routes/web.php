<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.post');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/bots', [\App\Http\Controllers\DashboardController::class, 'botsIndex'])->name('dashboard.bots');
    Route::get('/dashboard/history/{bot}', [\App\Http\Controllers\DashboardController::class, 'botHistory'])->name('dashboard.history');

    // API & Actions
    Route::post('/chat', [\App\Http\Controllers\DashboardController::class, 'chat'])->name('chat');
    Route::get('/notifications', [\App\Http\Controllers\DashboardController::class, 'notifications'])->name('notifications');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
