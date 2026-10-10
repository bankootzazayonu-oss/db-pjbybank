<?php

namespace App\Http\Controllers\Admin;
use App\Models\Type;
use App\Models\Platform;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Activity;

class TmdbController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('query');
    $movies = [];

    if ($query) {
        $apiKey = env('TMDB_API_KEY');

        $response = Http::get(
            "https://api.themoviedb.org/3/search/movie",
            [
                'api_key' => $apiKey,
                'query' => $query,
                'language' => 'th-TH',
            ]
        );

        if ($response->successful()) {
            $movies = $response->json()['results'] ?? [];
        }
    }


        $types = Type::orderBy('name')->get();
        $platforms = Platform::orderBy('name')->get();


        $existingTmdbIds = Activity::whereNotNull('tmdb_id')->pluck('tmdb_id')->toArray();
        $existingNames = Activity::pluck('name')
            ->concat(Activity::whereNotNull('original_title')->pluck('original_title'))
            ->map(fn($n) => strtolower(trim($n)))
            ->unique()
            ->toArray();

        return view(
            'admin.movies.search',
            compact('movies', 'query', 'types', 'existingTmdbIds', 'existingNames', 'platforms')
        );
    }


    public function import(Request $request)
{
    $request->validate([
        'tmdb_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'original_title' => 'nullable|string|max:255',
        'year' => 'required|integer',
        'overview' => 'nullable|string',
        'poster_path' => 'nullable|string',
        'type_id' => 'required|exists:types,id',
        'platforms' => 'nullable|array',
        'platforms.*' => 'exists:platforms,id',
    ]);

    $isTmdbDuplicate = Activity::where(
        'tmdb_id',
        $request->tmdb_id
    )->exists();

    if ($isTmdbDuplicate) {
        return back()->with(
            'error',
            '️ หนังเรื่อง "' .
            $request->title .
            '" มีอยู่ในระบบแล้ว (TMDB ID ซ้ำ)'
        );
    }

    $isDuplicate = Activity::where(function($query) use ($request) {
            $query->whereRaw('LOWER(name) = ?', [strtolower($request->title)])
                  ->orWhere(function($sub) use ($request) {
                      if ($request->filled('original_title')) {
                          $sub->whereRaw('LOWER(original_title) = ?', [strtolower($request->original_title)]);
                      }
                  });
        })
        ->where('year', $request->year)
        ->exists();

    if ($isDuplicate) {
        return back()->with(
            'error',
            '️ หนังเรื่อง "' .
            $request->title .
            '" ปี ' .
            $request->year .
            ' มีอยู่ในระบบแล้ว'
        );
    }

    $imagePath = null;

    if ($request->filled('poster_path')) {
        $imagePath =
            'https://image.tmdb.org/t/p/w500' .
            $request->poster_path;
    }

    $activity = Activity::create([
        'name' => $request->title,
        'original_title' => $request->original_title ?: null,
        'year' => $request->year,
        'review' => $request->overview,
        'image' => $imagePath,
        'hours' => 2.0,
        'type_id' => $request->type_id,
        'tmdb_id' => $request->tmdb_id,
        'is_approved' => 1,
        'status' => 'approved',
        'user_id' => auth()->id(),
    ]);

    if ($request->filled('platforms')) {
        $activity->platforms()->sync($request->platforms);
    }

    return back()->with(
        'success',
        ' นำเข้า "' .
        $request->title .
        '" เข้าสู่คลังภาพยนตร์สำเร็จ!'
    );
}
}