<?php

use App\Models\Activity;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Controllers\Admin\TmdbController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CollectionController;
use App\Http\Middleware\IsAdmin;

// =====================================
// โซน PUBLIC (ใครๆ ก็เข้าได้ ไม่ต้องล็อกอิน)
// =====================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// หน้า Leaderboard แบบไม่ต้องล็อกอิน
Route::get('/leaderboard', [ActivityController::class, 'leaderboard'])->name('leaderboard');


// =====================================
// โซน USER (ต้องล็อกอินถึงจะทำได้)
// =====================================
Route::middleware(['auth'])->group(function () {
    
    // หน้า Dashboard ของระบบ (หนังที่อนุมัติแล้ว)
    Route::get('/dashboard', function () {
        $movies = Activity::where('is_approved', true)->latest()->get(); 
        return view('dashboard', compact('movies'));
    })->name('dashboard');

    // ระบบ Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ระบบ จัดการภาพยนตร์ (ฝั่ง User)
    Route::get('/movies/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/movies/store', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/my-movies', [ActivityController::class, 'myMovies'])->name('my.movies');
    Route::get('/movies/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/movies/{id}', [ActivityController::class, 'update'])->name('activities.update');
    Route::get('/movies/{id}', [ActivityController::class, 'show'])->name('activities.show');

    // ระบบ รีวิว & รายงาน (คอมเมนต์)
    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'storeReply'])->name('replies.store');
    // 🟢 ย้าย Route นี้ออกมาให้ User ทั่วไปใช้งานได้แล้ว!
    Route::post('/reviews/{id}/report', [ReviewController::class, 'report'])->name('reviews.report');

    // ระบบ Collection / Tier List
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{id}', [CollectionController::class, 'show'])->name('collections.show');
    Route::post('/collections/{id}/add', [CollectionController::class, 'addMovie'])->name('collections.add');
    Route::put('/collections/item/{id}', [CollectionController::class, 'updateRank'])->name('collections.updateRank');
    Route::delete('/collections/item/{id}', [CollectionController::class, 'destroyItem'])->name('collections.destroyItem');
});


// =====================================
// โซน ADMIN (ต้องเป็น Admin เท่านั้น)
// =====================================
Route::middleware(['auth', IsAdmin::class])->group(function () {
    
    // ระบบจัดการหมวดหมู่และผู้กำกับ (Resource Controllers)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('types', TypeController::class);
        Route::resource('directors', DirectorController::class);
    });

    // ระบบจัดการหมวดหมู่แบบเจาะจง (ซ้ำกับ Resource ข้างบน แต่อาจใช้สำหรับฟอร์มเฉพาะ)
    Route::get('/admin/types', [TypeController::class, 'index'])->name('admin.types.index');
    Route::post('/admin/types', [TypeController::class, 'store'])->name('admin.types.store');
    Route::delete('/admin/types/{type}', [TypeController::class, 'destroy'])->name('admin.types.destroy');

    // ระบบจัดการ อนุมัติภาพยนตร์
    Route::get('/admin/pending-movies', [ActivityController::class, 'pending'])->name('admin.movies.pending');
    Route::post('/admin/approve-movie/{id}', [ActivityController::class, 'approve'])->name('admin.movies.approve');
    Route::delete('/movies/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');

    // ระบบจัดการ รายงานคอมเมนต์สแปม/ไม่เหมาะสม
    Route::get('/admin/reports', [ReviewController::class, 'adminReports'])->name('admin.reports');
    Route::delete('/admin/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::delete('/admin/reports/{id}/dismiss', [ReviewController::class, 'dismissReport'])->name('admin.reports.dismiss');

    // ระบบนำเข้าหนังจาก TMDB
    Route::get('/admin/movies/search', [TmdbController::class, 'search'])->name('admin.movies.search');
    Route::post('/admin/movies/import', [TmdbController::class, 'import'])->name('admin.movies.import');
});

require __DIR__.'/auth.php';