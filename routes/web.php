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
    
    // Clients
    Route::get('/dashboard/clients', [\App\Http\Controllers\DashboardController::class, 'clientsIndex'])->name('dashboard.clients');
    Route::post('/dashboard/clients', [\App\Http\Controllers\DashboardController::class, 'clientsStore'])->name('dashboard.clients.store');
    Route::patch('/dashboard/clients/{id}', [\App\Http\Controllers\DashboardController::class, 'clientsUpdate'])->name('dashboard.clients.update');
    Route::patch('/dashboard/clients/{id}/toggle-status', [\App\Http\Controllers\DashboardController::class, 'clientsToggleStatus'])->name('dashboard.clients.toggle-status');
    Route::post('/dashboard/clients/parse', [\App\Http\Controllers\DashboardController::class, 'clientsParse'])->name('dashboard.clients.parse');
    Route::delete('/dashboard/clients/{id}', [\App\Http\Controllers\DashboardController::class, 'clientsDestroy'])->name('dashboard.clients.destroy');

    // Courses
    Route::get('/dashboard/courses', [\App\Http\Controllers\DashboardController::class, 'coursesIndex'])->name('dashboard.courses');
    Route::post('/dashboard/courses', [\App\Http\Controllers\DashboardController::class, 'coursesStore'])->name('dashboard.courses.store');
    Route::patch('/dashboard/courses/{id}', [\App\Http\Controllers\DashboardController::class, 'coursesUpdate'])->name('dashboard.courses.update');
    Route::patch('/dashboard/courses/{id}/toggle-status', [\App\Http\Controllers\DashboardController::class, 'coursesToggleStatus'])->name('dashboard.courses.toggle-status');
    Route::post('/dashboard/courses/parse', [\App\Http\Controllers\DashboardController::class, 'coursesParse'])->name('dashboard.courses.parse');
    Route::delete('/dashboard/courses/{id}', [\App\Http\Controllers\DashboardController::class, 'coursesDestroy'])->name('dashboard.courses.destroy');
    
    // Facebook
    Route::get('/dashboard/facebook', [\App\Http\Controllers\DashboardController::class, 'facebookIndex'])->name('dashboard.facebook');
    Route::post('/dashboard/facebook', [\App\Http\Controllers\DashboardController::class, 'facebookStore'])->name('dashboard.facebook.store');
    Route::post('/dashboard/facebook/settings', [\App\Http\Controllers\DashboardController::class, 'facebookSettingsUpdate'])->name('dashboard.facebook.settings.update');
    Route::delete('/dashboard/facebook/{id}', [\App\Http\Controllers\DashboardController::class, 'facebookDestroy'])->name('dashboard.facebook.destroy');

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
