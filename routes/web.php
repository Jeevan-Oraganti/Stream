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


//notices routes
Route::middleware('can:admin')->group(function () {
    Route::get('/admin/dashboard', [NoticeController::class, 'dashboard']);
    Route::get('/admin/notices', [NoticeController::class, 'showNoticesListForAdmin'])->name('notices.index');
    Route::get('/admin/notices/all', [NoticeController::class, 'fetchAllNoticesForAdmin'])->name('notices.index');
    Route::post('/admin/notice/createOrUpdate', [NoticeController::class, 'createOrUpdateNotice'])->name('notice.store');
    Route::post('/admin/notice/{noticeId}/delete', [NoticeController::class, 'noticeDestroy']);
    Route::post('/admin/notice/{noticeId}/toggle-sticky', [NoticeController::class, 'toggleSticky']);
    Route::get('/admin/notice-types', [NoticeController::class, 'getNoticeTypes']);
    Route::post('/admin/notice-type/{noticeTypeId}/change-color', [NoticeController::class, 'changeNoticeTypeColorPost']);
});


Route::get('/notices', [NoticeController::class, 'show']);
Route::get('/notices/unread', [NoticeController::class, 'unreadNotices']);

Route::middleware('auth')->group(function () {
    Route::post('/notice/{noticeId}/acknowledge', [NoticeController::class, 'storeUserDismissedNotices']);
});
