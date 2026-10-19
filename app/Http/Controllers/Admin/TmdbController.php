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


        $types = Type::orderBy('Genre_Name')->get();
        $platforms = Platform::orderBy('Platform_Name')->get();

        $existingTmdbIds = Activity::whereNotNull('TMDB_ID')->pluck('TMDB_ID')->toArray();
        $existingNames = Activity::pluck('Title')
            ->concat(Activity::whereNotNull('Original_Title')->pluck('Original_Title'))
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
            'type_id' => 'required|exists:types,Genre_ID',
            'platforms' => 'nullable|array',
            'platforms.*' => 'exists:platforms,Platform_ID',
        ]);

        $isTmdbDuplicate = Activity::where(
            'TMDB_ID',
            $request->tmdb_id
        )->exists();

        if ($isTmdbDuplicate) {
            return back()->with(
                'error',
                'ภาพยนตร์เรื่อง "' .
                $request->title .
                '" มีอยู่ในระบบแล้ว (TMDB ID ซ้ำ)'
            );
        }

        $isDuplicate = Activity::where(function($query) use ($request) {
                $query->whereRaw('LOWER(Title) = ?', [strtolower($request->title)])
                      ->orWhere(function($sub) use ($request) {
                          if ($request->filled('original_title')) {
                              $sub->whereRaw('LOWER(Original_Title) = ?', [strtolower($request->original_title)]);
                          }
                      });
            })
            ->where('Release_Year', $request->year)
            ->exists();

        if ($isDuplicate) {
            return back()->with(
                'error',
                'ภาพยนตร์เรื่อง "' .
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
            'Title' => $request->title,
            'Original_Title' => $request->original_title ?: null,
            'Release_Year' => $request->year,
            'Description' => $request->overview ?: '',
            'Poster' => $imagePath,
            'Duration_Hours' => 2.0,
            'Genre_ID' => $request->type_id,
            'TMDB_ID' => $request->tmdb_id,
            'Approval_Status' => 'approved',
            'Submitted_By' => auth()->id(),
        ]);

        if ($request->filled('platforms')) {
            $activity->platforms()->sync($request->platforms);
        }

        return back()->with(
            'success',
            'นำเข้าภาพยนตร์เรื่อง "' .
            $request->title .
            '" เข้าสู่คลังภาพยนตร์สำเร็จ'
        );
    }
}