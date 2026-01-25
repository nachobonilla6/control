<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
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
    
    // Webhooks
    Route::get('/dashboard/webhooks', [\App\Http\Controllers\DashboardController::class, 'webhooksIndex'])->name('dashboard.webhooks');
    Route::post('/dashboard/webhooks', [\App\Http\Controllers\DashboardController::class, 'webhooksStore'])->name('dashboard.webhooks.store');
    Route::put('/dashboard/webhooks/{id}', [\App\Http\Controllers\DashboardController::class, 'webhooksUpdate'])->name('dashboard.webhooks.update');
    Route::post('/dashboard/webhooks/{id}/trigger', [\App\Http\Controllers\DashboardController::class, 'webhooksTrigger'])->name('dashboard.webhooks.trigger');
    Route::delete('/dashboard/webhooks/{id}', [\App\Http\Controllers\DashboardController::class, 'webhooksDestroy'])->name('dashboard.webhooks.destroy');

    // Projects
    Route::get('/dashboard/projects', [\App\Http\Controllers\DashboardController::class, 'projectsIndex'])->name('dashboard.projects');
    Route::post('/dashboard/projects', [\App\Http\Controllers\DashboardController::class, 'projectsStore'])->name('dashboard.projects.store');
    Route::patch('/dashboard/projects/{id}', [\App\Http\Controllers\DashboardController::class, 'projectsUpdate'])->name('dashboard.projects.update');
    Route::delete('/dashboard/projects/{id}', [\App\Http\Controllers\DashboardController::class, 'projectsDestroy'])->name('dashboard.projects.destroy');
    Route::get('/projects/{id}', [\App\Http\Controllers\DashboardController::class, 'projectsShow'])->name('projects.show');

    // API & Actions
    Route::post('/chat', [\App\Http\Controllers\DashboardController::class, 'chat'])->name('chat');
    Route::get('/notifications', [\App\Http\Controllers\DashboardController::class, 'notifications'])->name('notifications');
    Route::post('/profile/update', [\App\Http\Controllers\DashboardController::class, 'profileUpdate'])->name('dashboard.profile.update');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
