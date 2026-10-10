<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
class ActivityController extends Controller
{
    public function show($id)
    {

        $movie = \App\Models\Activity::with(['type', 'reviews.user', 'platforms'])->findOrFail($id);
        

        $userReview = auth()->check() ? $movie->reviews()->where('user_id', auth()->id())->first() : null;

        return view('activities.show', compact('movie', 'userReview'));
    }

    public function leaderboard()
    {

        $topMovies = Activity::where('status', 'approved')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews') 
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        return view('activities.leaderboard', compact('topMovies'));
    }

    public function pending()
{
    $movies = Activity::where('status', 'pending')
        ->latest()
        ->get();

    return view('admin.pending', compact('movies'));
}

   public function approve($id)
{
    $movie = Activity::findOrFail($id);




    if (!empty($movie->tmdb_id)) {
        $isTmdbDuplicate = Activity::where('tmdb_id', $movie->tmdb_id)
            ->where('id', '!=', $movie->id)
            ->exists();

        if ($isTmdbDuplicate) {
            return back()->with(
                'error',
                ' ไม่สามารถอนุมัติได้ เพราะภาพยนตร์เรื่อง "' .
                $movie->name .
                '" มี TMDB ID ซ้ำกับภาพยนตร์ในระบบแล้ว'
            );
        }
    }




    $isDuplicate = Activity::whereRaw(
        'LOWER(name) = ?',
        [strtolower($movie->name)]
    )
        ->where('year', $movie->year)
        ->where('id', '!=', $movie->id)
        ->exists();

    if ($isDuplicate) {
        return back()->with(
            'error',
            ' ไม่สามารถอนุมัติได้ เพราะภาพยนตร์เรื่อง "' .
            $movie->name .
            '" ปี ' .
            $movie->year .
            ' มีอยู่ในระบบแล้ว'
        );
    }




    $movie->update([
    'is_approved' => 1,
    'status' => 'approved',
]);

    return back()->with(
        'success',
        ' อนุมัติภาพยนตร์เรื่อง "' .
        $movie->name .
        '" เรียบร้อยแล้ว'
    );
}

public function reject($id)
{
    $movie = Activity::findOrFail($id);

    $movie->update([
        'is_approved' => 0,
        'status' => 'rejected',
    ]);

    return back()->with(
        'success',
        ' ปฏิเสธภาพยนตร์เรื่อง "' .
        $movie->name .
        '" เรียบร้อยแล้ว'
    );
}
   public function create()
    {

        $types = \App\Models\Type::orderBy('name')->get(); 
        $platforms = \App\Models\Platform::orderBy('name')->get();

        return view('activities.create', compact('types', 'platforms'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'original_title' => 'nullable|string|max:255',
        'year' => 'required|integer',
        'review' => 'required|string',
        'type_id' => 'required|exists:types,id',
        'image' => 'nullable|image|max:5120',
        'api_image' => 'nullable|string',
        'tmdb_id' => 'nullable|integer',
        'platforms' => 'nullable|array',
        'platforms.*' => 'exists:platforms,id',
    ]);




    if ($request->filled('tmdb_id')) {
        $isTmdbDuplicate = Activity::where(
            'tmdb_id',
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
            $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                  ->orWhere(function($sub) use ($request) {
                      if ($request->filled('original_title')) {
                          $sub->whereRaw('LOWER(original_title) = ?', [strtolower($request->original_title)]);
                      }
                  });
        })
        ->where('year', $request->year)
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
        'name' => $request->name,
        'original_title' => $request->original_title ?: null,
        'year' => $request->year,
        'review' => $request->review,
        'image' => $imagePath,
        'user_id' => auth()->id(),
        'hours' => 0,
        'type_id' => $request->type_id,
        'tmdb_id' => $request->tmdb_id,
        'is_approved' => 0,
        'status' => 'pending',
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
        $movies = Activity::where('user_id', auth()->id())->latest()->get();
        return view('activities.my_movies', compact('movies'));
    }


    public function edit($id)
    {
        $activity = Activity::findOrFail($id);


        if (auth()->user()->role !== 'admin') {
            if (
    $activity->user_id !== auth()->id()
    || $activity->status === 'approved'
) {
                return redirect()->route('my.movies')->with('error', ' คุณไม่มีสิทธิ์แก้ไข หรือภาพยนตร์ถูกอนุมัติไปแล้ว');
            }
        }

        $types = \App\Models\Type::orderBy('name')->get();
        $platforms = \App\Models\Platform::orderBy('name')->get();
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
    auth()->user()->role !== 'admin'
    && (
        $activity->user_id !== auth()->id()
        || $activity->status === 'approved'
    )
) {
            return redirect()->route('my.movies')->with('error', ' ไม่อนุญาตให้แก้ไขข้อมูล');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'review' => 'required|string',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|image|max:5120',
            'api_image' => 'nullable|string', 
            'platforms' => 'nullable|array',
            'platforms.*' => 'exists:platforms,id',
        ]);
        

        $isDuplicate = \App\Models\Activity::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                                           ->where('year', $request->year)
                                           ->where('id', '!=', $id)
                                           ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors(['name' => ' ไม่สามารถเปลี่ยนชื่อเป็นเรื่องนี้ได้ เพราะมีอยู่ในคลังแล้วครับ!']);
        }

        $updateData = [
            'name' => $request->name,
            'year' => $request->year,
            'review' => $request->review,
            'type_id' => $request->type_id,
        ];


if (auth()->user()->role !== 'admin' && $activity->status === 'rejected') {
    $updateData['status'] = 'pending';
    $updateData['is_approved'] = 0;
}


        if ($request->hasFile('image')) {
            $updateData['image'] = $request->file('image')->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $updateData['image'] = $request->api_image;
        }

        $activity->update($updateData);


        $activity->platforms()->sync($request->platforms ?? []);

        return redirect()->route('my.movies')->with('success', ' อัปเดตข้อมูลภาพยนตร์เรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        $movie = Activity::findOrFail($id);


        if (auth()->user()->role !== 'admin' && $movie->user_id !== auth()->id()) {
            return back()->with('error', ' คุณไม่มีสิทธิ์ลบรายการนี้');
        }


        

        $movie->delete();

                $previousUrl = url()->previous();
        if (str_contains($previousUrl, '/movies/' . $id)) {
            return redirect()->route('dashboard')->with('success', '️ ลบภาพยนตร์เข้าถังขยะเรียบร้อยแล้ว');
        }
        return back()->with('success', '️ ลบ/ปัดตกข้อมูลภาพยนตร์เรียบร้อยแล้ว');
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
        $movies = Activity::onlyTrashed()->latest()->paginate(20);
        return view('admin.movies.trash', compact('movies'));
    }

    public function restore($id)
    {
        $movie = Activity::onlyTrashed()->findOrFail($id);
        $movie->restore();
        return back()->with('success', 'กู้คืนภาพยนตร์ ' . $movie->name . ' สำเร็จ!');
    }

    public function forceDelete($id)
    {
        $movie = Activity::onlyTrashed()->findOrFail($id);
        

        if ($movie->image && !str_starts_with($movie->image, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($movie->image);
        }
        
        $movie->forceDelete();
        return back()->with('success', 'ลบภาพยนตร์ ' . $movie->name . ' ถาวรสำเร็จ!');
    }
}