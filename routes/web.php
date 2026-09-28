<?php
use App\Models\Activity;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// 🟢 เพิ่ม Import Controllers และ Middleware ของเราตรงนี้
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\DirectorController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\TmdbController;

Route::get('/', function () {
    return view('welcome');
})->name('home'); // 👈 เติมตรงนี้

Route::get('/dashboard', function () {
    // เปลี่ยนจาก Activity::latest() เป็นการเช็ก is_approved
    $movies = \App\Models\Activity::where('is_approved', true)->latest()->get(); 
    return view('dashboard', compact('movies'));
})->middleware(['auth'])->name('dashboard');

// Route::get('/movies/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🟢 โซนของ Admin (ถูกป้องกันด้วย IsAdmin Middleware)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('types', TypeController::class);
    Route::resource('directors', DirectorController::class);
});
Route::middleware([App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/admin/pending-movies', [App\Http\Controllers\ActivityController::class, 'pending'])->name('admin.movies.pending');
    Route::post('/admin/approve-movie/{id}', [App\Http\Controllers\ActivityController::class, 'approve'])->name('admin.movies.approve');
});


// Route::middleware(['auth'])->group(function () {
//     // ผู้ใช้ทั่วไปเข้าหน้าฟอร์มเพิ่มหนัง
//     Route::get('/movies/create', [App\Http\Controllers\ActivityController::class, 'create'])->name('activities.create');
//     Route::post('/movies/store', [App\Http\Controllers\ActivityController::class, 'store'])->name('activities.store');
// });

Route::middleware(['auth'])->group(function () {
    // ... (Route อื่นๆ ที่มีอยู่แล้ว) ...

    // ให้ผู้ใช้ทั่วไปเข้าหน้าฟอร์มเพิ่มหนังได้
    Route::get('/movies/create', [App\Http\Controllers\ActivityController::class, 'create'])->name('activities.create');
    Route::post('/movies/store', [App\Http\Controllers\ActivityController::class, 'store'])->name('activities.store');

    Route::get('/movies/{id}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
});

Route::get('/admin/movies/search', [TmdbController::class, 'search'])->name('admin.movies.search');
Route::post('/admin/movies/import', [TmdbController::class, 'import'])->name('admin.movies.import');


Route::post('/movies/{id}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

Route::post('/reviews/{id}/replies', [App\Http\Controllers\ReviewReplyController::class, 'store'])->name('replies.store')->middleware('auth');

Route::get('/leaderboard', [App\Http\Controllers\ActivityController::class, 'leaderboard'])->name('leaderboard');

require __DIR__.'/auth.php';