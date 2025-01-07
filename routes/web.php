<?php

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

Route::get('/tabs', function () {
    return view('layouts.tabs');
});

Route::get('/tabs/{id}/content', [TabController::class, 'getContent']);