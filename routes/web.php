<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TabController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::get('/', function () {
    return view('welcome');
});

Route::get('/statuses', [StatusController::class, 'index']);

Route::post('/statuses', [StatusController::class, 'store']);

Route::get('/tabs/{slug}/content', [TabController::class, 'getContent']);

Route::get('/login', [SessionController::class, 'login']);
Route::post('/login', [SessionController::class, 'verify']);

Route::get('/register', function () {
    return view('session.register');
});

Route::post('/register', [SessionController::class, 'store']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');


Route::middleware('can:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/notices', [AdminController::class, 'noticeIndex'])->name('admin.notices.index');
    Route::post('/admin/notice', [AdminController::class, 'noticeStore'])->name('admin.notice.store');
    Route::post('/admin/notice/{noticeId}', [AdminController::class, 'noticeUpdate'])->name('admin.notice.update');
    Route::delete('/admin/notice/{noticeId}', [AdminController::class, 'noticeDestroy']);
    Route::get('/admin/add-notice', [AdminController::class, 'addNotice']);
    Route::get('/admin/edit-notice/{noticeId}', [AdminController::class, 'editNotice']);
});


Route::get('/notice', [NoticeController::class, 'getLatestNotice']);

Route::middleware('auth')->group(function () {
    Route::get('/notices/unread', [NoticeController::class, 'unread']);
    Route::post('/notice/{noticeId}/acknowledge', [NoticeController::class, 'acknowledge']);
});
