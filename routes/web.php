<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\SessionController;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TabController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/statuses', [StatusController::class, 'index']);

Route::post('/statuses', [StatusController::class, 'store']);

Route::get('/tabs/{slug}/content', [TabController::class, 'getContent']);

Route::get('/login', [SessionController::class, 'login']);
Route::post('/login', [SessionController::class, 'verify']);

Route::get('/register', function () { return view('session.register');});

Route::post('/register', [SessionController::class, 'store']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');


Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware('auth')->get('/analytics', [AnalyticsController::class, 'index']);
