<?php

use App\Http\Controllers\AnalyticsController;
use App\Models\Status;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TabController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/statuses', [StatusController::class, 'index']);

Route::post('/statuses', [StatusController::class, 'store']);

Route::get('/tabs/{slug}/content', [TabController::class, 'getContent']);
Route::get('/tabs', [TabController::class, 'getAll']);

Route::get('/analytics', [AnalyticsController::class, 'index']);
