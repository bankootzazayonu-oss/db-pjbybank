<?php

namespace App\Http\Controllers\Admin;
use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Activity; // ดึง Model หนังมาใช้

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

        // ดึง Genre/Type จากฐานข้อมูล
        $types = Type::orderBy('name')->get();

        // ตรวจสอบ TMDB ID และชื่อที่มีในระบบแล้ว
        $existingTmdbIds = Activity::whereNotNull('tmdb_id')->pluck('tmdb_id')->toArray();
        $existingNames = Activity::pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();

        return view(
            'admin.movies.search',
            compact('movies', 'query', 'types', 'existingTmdbIds', 'existingNames')
        );
    }

    // ฟังก์ชันใหม่สำหรับบันทึกลงฐานข้อมูล
    public function import(Request $request)
{
    $request->validate([
        'tmdb_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'year' => 'required|integer',
        'overview' => 'nullable|string',
        'poster_path' => 'nullable|string',
        'type_id' => 'required|exists:types,id',
    ]);

    $isTmdbDuplicate = Activity::where(
        'tmdb_id',
        $request->tmdb_id
    )->exists();

    if ($isTmdbDuplicate) {
        return back()->with(
            'error',
            '⚠️ หนังเรื่อง "' .
            $request->title .
            '" มีอยู่ในระบบแล้ว (TMDB ID ซ้ำ)'
        );
    }

    $isDuplicate = Activity::whereRaw(
        'LOWER(name) = ?',
        [strtolower($request->title)]
    )
        ->where('year', $request->year)
        ->exists();

    if ($isDuplicate) {
        return back()->with(
            'error',
            '⚠️ หนังเรื่อง "' .
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

    Activity::create([
        'name' => $request->title,
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

    return back()->with(
        'success',
        '✅ นำเข้า "' .
        $request->title .
        '" เข้าสู่คลังภาพยนตร์สำเร็จ!'
    );
}
}