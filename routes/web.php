<?php

use App\Models\Activity;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\TmdbController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CollectionController;
use App\Http\Middleware\IsAdmin;
use App\Models\Review;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




Route::get('/', function (Request $request) {
    $search = $request->query('q');
    $typeId = $request->query('type');

    $query = Activity::where('is_approved', true)->where('status', 'approved');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('original_title', 'like', '%' . $search . '%');
        });
    }

    if ($typeId) {
        $query->where('type_id', $typeId);
    }

    $featuredMovies = $query->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->with('type')
        ->latest()
        ->take(12)
        ->get();

    $recentReviews = Review::with(['user', 'activity'])
        ->whereHas('activity', function ($q) {
            $q->where('is_approved', true)->where('status', 'approved');
        })
        ->latest()
        ->take(3)
        ->get();

    $types = Type::orderBy('name')->get();
    $totalMovies = Activity::where('is_approved', true)->where('status', 'approved')->count();
    $totalReviews = Review::count();

    return view('welcome', compact('featuredMovies', 'recentReviews', 'types', 'totalMovies', 'totalReviews', 'search', 'typeId'));
})->name('home');


Route::get('/leaderboard', [ActivityController::class, 'leaderboard'])->name('leaderboard');





Route::middleware(['auth'])->group(function () {
    

   Route::get('/dashboard', function (Request $request) {
    $search = $request->query('q');
    $typeId = $request->query('type');

    $movieQuery = Activity::where('is_approved', true)
        ->where('status', 'approved');

    if ($search) {
        $movieQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('original_title', 'like', '%' . $search . '%');
        });
    }

    if ($typeId) {
        $movieQuery->where('type_id', $typeId);
    }

    $movies = $movieQuery->latest()->get();
    $types = Type::orderBy('name')->get();


    $totalMovies = Activity::where('is_approved', true)
        ->where('status', 'approved')
        ->count();


    $totalReviews = Review::whereHas('activity', function ($query) {
        $query->where('is_approved', true)
            ->where('status', 'approved');
    })->count();


    $overallAverageRating = Review::whereHas('activity', function ($query) {
        $query->where('is_approved', true)
            ->where('status', 'approved');
    })->avg('rating');


    $topGenres = Type::query()
        ->select(
            'types.id',
            'types.name',
            DB::raw('AVG(reviews.rating) as average_rating'),
            DB::raw('COUNT(reviews.id) as review_count')
        )
        ->join('activities', 'activities.type_id', '=', 'types.id')
        ->join('reviews', 'reviews.activity_id', '=', 'activities.id')
        ->where('activities.is_approved', true)
        ->where('activities.status', 'approved')
        ->groupBy('types.id', 'types.name')
        ->orderByDesc('average_rating')
        ->orderByDesc('review_count')
        ->limit(5)
        ->get();

    return view('dashboard', compact(
        'movies',
        'types',
        'search',
        'typeId',
        'totalMovies',
        'totalReviews',
        'overallAverageRating',
        'topGenres'
    ));

})->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/movies/create', [ActivityController::class, 'create'])->name('activities.create');

    Route::get('/movies/tmdb-search', [ActivityController::class, 'tmdbSearch'])
    ->name('activities.tmdb_search');

    Route::post('/movies/store', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/my-movies', [ActivityController::class, 'myMovies'])->name('my.movies');
    Route::get('/movies/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/movies/{id}', [ActivityController::class, 'update'])->name('activities.update');
    Route::get('/movies/{id}', [ActivityController::class, 'show'])->name('activities.show');


    Route::post('/movies/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{id}/reply', [ReviewController::class, 'storeReply'])->name('replies.store');


    Route::put('/replies/{id}', [\App\Http\Controllers\ReviewReplyController::class, 'update'])->name('replies.update');
    Route::delete('/replies/{id}', [\App\Http\Controllers\ReviewReplyController::class, 'destroy'])->name('replies.destroy');
    
    
    

    Route::post('/reviews/{id}/report', [ReviewController::class, 'report'])->name('reviews.report');


    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{id}', [CollectionController::class, 'show'])->name('collections.show');
    Route::post('/collections/{id}/add', [CollectionController::class, 'addMovie'])->name('collections.add');
    Route::put('/collections/item/{id}', [CollectionController::class, 'updateRank'])->name('collections.updateRank');
    Route::delete('/collections/item/{id}', [CollectionController::class, 'destroyItem'])->name('collections.destroyItem');



    Route::put('/collections/{id}', [\App\Http\Controllers\CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{id}', [\App\Http\Controllers\CollectionController::class, 'destroy'])->name('collections.destroy');
    

    Route::put('/collections/{id}/labels', [\App\Http\Controllers\CollectionController::class, 'updateLabels'])->name('collections.updateLabels');


    Route::get('/collections-trash/view', [\App\Http\Controllers\CollectionController::class, 'trash'])->name('collections.trash');
    Route::post('/collections/{id}/restore', [\App\Http\Controllers\CollectionController::class, 'restore'])->name('collections.restore');
    Route::delete('/collections/{id}/force-delete', [\App\Http\Controllers\CollectionController::class, 'forceDelete'])->name('collections.forceDelete');


    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}/user', [ReviewController::class, 'userDestroy'])->name('reviews.user_destroy');



    Route::get('/tmdb/search-json', [\App\Http\Controllers\CollectionController::class, 'searchTmdb'])->name('tmdb.search_json');
    Route::post('/collections/{id}/add-tmdb', [\App\Http\Controllers\CollectionController::class, 'storeFromTmdb'])->name('collections.store_tmdb');



});





Route::middleware(['auth', IsAdmin::class])->group(function () {
    

Route::resource('types', TypeController::class);

    Route::get('/admin/types', [TypeController::class, 'index'])->name('admin.types.index');
    Route::post('/admin/types', [TypeController::class, 'store'])->name('admin.types.store');
    Route::delete('/admin/types/{type}', [TypeController::class, 'destroy'])->name('admin.types.destroy');

    Route::get('/admin/types', [\App\Http\Controllers\Admin\TypeController::class, 'index'])->name('admin.types.index');
    Route::post('/admin/types', [\App\Http\Controllers\Admin\TypeController::class, 'store'])->name('admin.types.store');

    Route::put('/admin/types/{type}', [\App\Http\Controllers\Admin\TypeController::class, 'update'])->name('admin.types.update');
    Route::delete('/admin/types/{type}', [\App\Http\Controllers\Admin\TypeController::class, 'destroy'])->name('admin.types.destroy');


    Route::get('/admin/movies/trash', [\App\Http\Controllers\ActivityController::class, 'trash'])->name('admin.movies.trash');
    Route::post('/admin/movies/{id}/restore', [\App\Http\Controllers\ActivityController::class, 'restore'])->name('admin.movies.restore');
    Route::delete('/admin/movies/{id}/force-delete', [\App\Http\Controllers\ActivityController::class, 'forceDelete'])->name('admin.movies.forceDelete');

    Route::get('/admin/pending-movies', [ActivityController::class, 'pending'])->name('admin.movies.pending');
    Route::post('/admin/approve-movie/{id}', [ActivityController::class, 'approve'])->name('admin.movies.approve');

        Route::post('/admin/reject-movie/{id}', [\App\Http\Controllers\ActivityController::class, 'reject'])->name('admin.movies.reject');
    Route::get('/admin/movies/{id}/edit', [\App\Http\Controllers\ActivityController::class, 'edit'])->name('admin.movies.edit');
    Route::put('/admin/movies/{id}', [\App\Http\Controllers\ActivityController::class, 'update'])->name('admin.movies.update');
    Route::delete('/movies/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');


    Route::get('/admin/reports', [ReviewController::class, 'adminReports'])->name('admin.reports');
    Route::delete('/admin/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::delete('/admin/reports/{id}/dismiss', [ReviewController::class, 'dismissReport'])->name('admin.reports.dismiss');


    Route::get('/admin/movies/search', [TmdbController::class, 'search'])->name('admin.movies.search');
    Route::post('/admin/movies/import', [TmdbController::class, 'import'])->name('admin.movies.import');


    Route::get('/admin/platforms', [PlatformController::class, 'index'])->name('admin.platforms.index');
    Route::post('/admin/platforms', [PlatformController::class, 'store'])->name('admin.platforms.store');
    Route::put('/admin/platforms/{platform}', [PlatformController::class, 'update'])->name('admin.platforms.update');
    Route::delete('/admin/platforms/{platform}', [PlatformController::class, 'destroy'])->name('admin.platforms.destroy');
});

require __DIR__.'/auth.php';