<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Type;
use App\Models\Platform;
use App\Models\CommentReport;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ActivityController extends Controller
{
    public function show($id)
    {
        $movie = Activity::with(['type', 'reviews.user', 'platforms'])->findOrFail($id);
        
        $userReview = auth()->check() ? $movie->reviews()->where('User_ID', auth()->id())->first() : null;

        return view('activities.show', compact('movie', 'userReview'));
    }

    public function leaderboard()
    {
        $topMovies = Activity::where('Approval_Status', 'approved')
            ->withAvg('reviews as reviews_avg_rating', 'Score')
            ->withAvg('reviews as reviews_avg_score', 'Score')
            ->withCount('reviews')
            ->has('reviews') 
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return view('activities.leaderboard', compact('topMovies'));
    }

    public function pending()
    {
        $movies = Activity::where('Approval_Status', 'pending')
            ->latest('CreatedAt')
            ->get();

        return view('admin.pending', compact('movies'));
    }

    public function approve($id)
    {
        $movie = Activity::findOrFail($id);

        if (!empty($movie->TMDB_ID)) {
            $isTmdbDuplicate = Activity::where('TMDB_ID', $movie->TMDB_ID)
                ->where('Movie_ID', '!=', $movie->Movie_ID)
                ->exists();

            if ($isTmdbDuplicate) {
                return back()->with(
                    'error',
                    'ไม่สามารถอนุมัติได้ เนื่องจากภาพยนตร์เรื่อง "' .
                    $movie->Title .
                    '" มี TMDB ID ซ้ำกับภาพยนตร์ในระบบแล้ว'
                );
            }
        }

        $isDuplicate = Activity::whereRaw(
            'LOWER(Title) = ?',
            [strtolower($movie->Title)]
        )
            ->where('Release_Year', $movie->Release_Year)
            ->where('Movie_ID', '!=', $movie->Movie_ID)
            ->exists();

        if ($isDuplicate) {
            return back()->with(
                'error',
                'ไม่สามารถอนุมัติได้ เนื่องจากภาพยนตร์เรื่อง "' .
                $movie->Title .
                '" ปี ' .
                $movie->Release_Year .
                ' มีอยู่ในระบบแล้ว'
            );
        }

        $movie->update([
            'Approval_Status' => 'approved',
        ]);

        return back()->with(
            'success',
            'อนุมัติภาพยนตร์เรื่อง "' .
            $movie->Title .
            '" เรียบร้อยแล้ว'
        );
    }

    public function reject($id)
    {
        $movie = Activity::findOrFail($id);

        $movie->update([
            'Approval_Status' => 'rejected',
        ]);

        return back()->with(
            'success',
            'ปฏิเสธภาพยนตร์เรื่อง "' .
            $movie->Title .
            '" เรียบร้อยแล้ว'
        );
    }

    public function create()
    {
        $types = Type::orderBy('Genre_Name')->get(); 
        $platforms = Platform::orderBy('Platform_Name')->get();

        return view('activities.create', compact('types', 'platforms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'original_title' => 'nullable|string|max:255',
            'year' => 'required|integer',
            'review' => 'required|string',
            'type_id' => 'required|exists:types,Genre_ID',
            'image' => 'nullable|image|max:5120',
            'api_image' => 'nullable|string',
            'tmdb_id' => 'nullable|integer',
            'platforms' => 'nullable|array',
            'platforms.*' => 'exists:platforms,Platform_ID',
        ]);

        if ($request->filled('tmdb_id')) {
            $isTmdbDuplicate = Activity::where(
                'TMDB_ID',
                $request->tmdb_id
            )->exists();

            if ($isTmdbDuplicate) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'name' => ' ภาพยนตร์เรื่องนี้มีอยู่ในระบบแล้ว (TMDB ID ซ้ำ)'
                    ]);
            }
        }

        $isDuplicate = Activity::where(function($query) use ($request) {
                $query->whereRaw('LOWER(Title) = ?', [strtolower($request->name)])
                      ->orWhere(function($sub) use ($request) {
                          if ($request->filled('original_title')) {
                              $sub->whereRaw('LOWER(Original_Title) = ?', [strtolower($request->original_title)]);
                          }
                      });
            })
            ->where('Release_Year', $request->year)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => ' ภาพยนตร์เรื่องนี้ (ปี ' .
                        $request->year .
                        ') มีอยู่ในคลังหรือกำลังรอตรวจสอบแล้วครับ!'
                ]);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request
                ->file('image')
                ->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $imagePath = $request->api_image;
        }

        $activity = Activity::create([
            'Title' => $request->name,
            'Original_Title' => $request->original_title ?: null,
            'Release_Year' => $request->year,
            'Description' => $request->review,
            'Poster' => $imagePath,
            'Submitted_By' => auth()->id(),
            'Duration_Hours' => 0,
            'Genre_ID' => $request->type_id,
            'TMDB_ID' => $request->tmdb_id,
            'Approval_Status' => 'pending',
        ]);

        if ($request->filled('platforms')) {
            $activity->platforms()->sync($request->platforms);
        }

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'ส่งข้อมูลภาพยนตร์สำเร็จ! กรุณารอแอดมินตรวจสอบอนุมัติครับ'
            );
    }

    public function myMovies()
    {
        $movies = Activity::where('Submitted_By', auth()->id())->latest('CreatedAt')->get();
        return view('activities.my_movies', compact('movies'));
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);

        if (auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin') {
            if (
                $activity->Submitted_By !== auth()->id()
                || $activity->Approval_Status === 'approved'
            ) {
                return redirect()->route('my.movies')->with('error', 'คุณไม่มีสิทธิ์แก้ไข หรือภาพยนตร์ถูกอนุมัติไปแล้ว');
            }
        }

        $types = Type::orderBy('Genre_Name')->get();
        $platforms = Platform::orderBy('Platform_Name')->get();
        $activity->load('platforms');

        return view('activities.edit', compact(
            'activity',
            'types',
            'platforms'
        ));
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        if (
            (auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin')
            && (
                $activity->Submitted_By !== auth()->id()
                || $activity->Approval_Status === 'approved'
            )
        ) {
            return redirect()->route('my.movies')->with('error', 'ไม่อนุญาตให้แก้ไขข้อมูล');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'review' => 'required|string',
            'type_id' => 'required|exists:types,Genre_ID',
            'image' => 'nullable|image|max:5120',
            'api_image' => 'nullable|string', 
            'platforms' => 'nullable|array',
            'platforms.*' => 'exists:platforms,Platform_ID',
        ]);
        
        $isDuplicate = Activity::whereRaw('LOWER(Title) = ?', [strtolower($request->name)])
            ->where('Release_Year', $request->year)
            ->where('Movie_ID', '!=', $id)
            ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors(['name' => 'ไม่สามารถเปลี่ยนชื่อเป็นเรื่องนี้ได้ เนื่องจากมีอยู่ในระบบแล้ว']);
        }

        $updateData = [
            'Title' => $request->name,
            'Release_Year' => $request->year,
            'Description' => $request->review,
            'Genre_ID' => $request->type_id,
        ];

        if ((auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin') && $activity->Approval_Status === 'rejected') {
            $updateData['Approval_Status'] = 'pending';
        }

        if ($request->hasFile('image')) {
            $updateData['Poster'] = $request->file('image')->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $updateData['Poster'] = $request->api_image;
        }

        $activity->update($updateData);

        $activity->platforms()->sync($request->platforms ?? []);

        return redirect()->route('my.movies')->with('success', 'อัปเดตข้อมูลภาพยนตร์เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $movie = Activity::withTrashed()->findOrFail($id);

        if ((auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin') && $movie->Submitted_By !== auth()->id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ลบรายการนี้');
        }

        $movie->delete();

        $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/movies/' . $id)) {
            return redirect()->route('dashboard')->with('success', 'ลบภาพยนตร์เรียบร้อยแล้ว');
        }
        return back()->with('success', 'ลบข้อมูลภาพยนตร์เรียบร้อยแล้ว');
    }

    public function tmdbSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'results' => [],
            ]);
        }

        $apiKey = env('TMDB_API_KEY');

        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => $apiKey,
            'query' => $query,
            'language' => 'th-TH',
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'ไม่สามารถเชื่อมต่อกับ TMDB ได้',
            ], 500);
        }

        return response()->json([
            'results' => $response->json()['results'] ?? [],
        ]);
    }

    public function trash()
    {
        $movies = Activity::onlyTrashed()->latest('CreatedAt')->paginate(20);
        return view('admin.movies.trash', compact('movies'));
    }

    public function restore($id)
    {
        $movie = Activity::onlyTrashed()->findOrFail($id);
        $movie->restore();
        return back()->with('success', 'กู้คืนภาพยนตร์ ' . $movie->Title . ' สำเร็จ!');
    }

    public function forceDelete($id)
    {
        $movie = Activity::onlyTrashed()->findOrFail($id);
        
        if ($movie->Poster && !str_starts_with($movie->Poster, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($movie->Poster);
        }
        
        $movie->forceDelete();
        return back()->with('success', 'ลบภาพยนตร์ ' . $movie->Title . ' ถาวรสำเร็จ!');
    }

    public function report($id)
    {
        return back()->with('success', 'กดรายงานเรียบร้อย รอ Admin ตรวจสอบ');
    }

    public function adminReports()
    {
        if (auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin') {
            abort(403);
        }
        $reports = CommentReport::with(['review.user', 'review.activity', 'user'])->latest('CreatedAt')->get();
        return view('admin.reports', compact('reports'));
    }

    public function dismissReport($id)
    {
        if (auth()->user()->Role !== 'admin' && auth()->user()->role !== 'admin') {
            abort(403);
        }
        $report = CommentReport::findOrFail($id);
        $report->delete();
        return back()->with('success', 'จัดการรายงานเรียบร้อยแล้ว');
    }
}