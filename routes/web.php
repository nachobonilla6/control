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
    Route::get('/dashboard/history/{bot}', [\App\Http\Controllers\DashboardController::class, 'botHistory'])->name('dashboard.history');

    // Chat message handling
    Route::post('/chat', [\App\Http\Controllers\DashboardController::class, 'chat'])->name('chat');

    // Fetch latest notifications as JSON for modal
    Route::get('/notifications', [\App\Http\Controllers\DashboardController::class, 'notifications'])->name('notifications');

    // New chat thread
    Route::post('/chat/new', [\App\Http\Controllers\DashboardController::class, 'newChat'])->name('chat.new');

    // Switch chat thread
    Route::get('/chat/{chatId}', [\App\Http\Controllers\DashboardController::class, 'showChat'])->name('chat.show');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
