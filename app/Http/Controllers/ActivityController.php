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
        // ดึงข้อมูลหนัง พร้อมหมวดหมู่ และรีวิว (เรียงจากใหม่ไปเก่า)
        $movie = \App\Models\Activity::with(['type', 'reviews.user'])->findOrFail($id);
        
        // เช็กว่า User คนนี้เคยรีวิวเรื่องนี้ไปหรือยัง (จะได้ไม่ให้รีวิวซ้ำ)
        $userReview = auth()->check() ? $movie->reviews()->where('user_id', auth()->id())->first() : null;

        return view('activities.show', compact('movie', 'userReview'));
    }

    public function leaderboard()
    {
        // ดึงหนังที่มีการอนุมัติแล้ว พร้อมคำนวณคะแนนเฉลี่ยและจำนวนรีวิว
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

    // ==========================================
    // 1. ตรวจสอบ TMDB ID ซ้ำ
    // ==========================================
    if (!empty($movie->tmdb_id)) {
        $isTmdbDuplicate = Activity::where('tmdb_id', $movie->tmdb_id)
            ->where('id', '!=', $movie->id)
            ->exists();

        if ($isTmdbDuplicate) {
            return back()->with(
                'error',
                '❌ ไม่สามารถอนุมัติได้ เพราะภาพยนตร์เรื่อง "' .
                $movie->name .
                '" มี TMDB ID ซ้ำกับภาพยนตร์ในระบบแล้ว'
            );
        }
    }

    // ==========================================
    // 2. ตรวจสอบชื่อ + ปีซ้ำ
    // ==========================================
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
            '❌ ไม่สามารถอนุมัติได้ เพราะภาพยนตร์เรื่อง "' .
            $movie->name .
            '" ปี ' .
            $movie->year .
            ' มีอยู่ในระบบแล้ว'
        );
    }

    // ==========================================
    // 3. อนุมัติ
    // ==========================================
    $movie->update([
    'is_approved' => 1,
    'status' => 'approved',
]);

    return back()->with(
        'success',
        '✅ อนุมัติภาพยนตร์เรื่อง "' .
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
        '❌ ปฏิเสธภาพยนตร์เรื่อง "' .
        $movie->name .
        '" เรียบร้อยแล้ว'
    );
}
   public function create()
    {
        // ดึงหมวดหมู่ทั้งหมดจากฐานข้อมูลเพื่อไปแสดงเป็น Dropdown
        $types = \App\Models\Type::orderBy('name')->get(); 
        return view('activities.create', compact('types'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'original_title' => 'nullable|string|max:255',
        'year' => 'required|integer',
        'review' => 'required|string',
        'type_id' => 'required|exists:types,id',
        'director_id' => 'nullable|exists:directors,id',
        'image' => 'nullable|image|max:5120',
        'api_image' => 'nullable|string',
        'tmdb_id' => 'nullable|integer',
    ]);

    // ==========================================
    // 1. ตรวจสอบ TMDB ID ซ้ำ
    // ==========================================
    if ($request->filled('tmdb_id')) {
        $isTmdbDuplicate = Activity::where(
            'tmdb_id',
            $request->tmdb_id
        )->exists();

        if ($isTmdbDuplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => '❌ ภาพยนตร์เรื่องนี้มีอยู่ในระบบแล้ว (TMDB ID ซ้ำ)'
                ]);
        }
    }

    // ==========================================
    // 2. ตรวจสอบชื่อ + ปี ซ้ำ
    // ==========================================
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
                'name' => '❌ ภาพยนตร์เรื่องนี้ (ปี ' .
                    $request->year .
                    ') มีอยู่ในคลังหรือกำลังรอตรวจสอบแล้วครับ!'
            ]);
    }

    // ==========================================
    // 3. จัดการรูปภาพ
    // ==========================================
    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request
            ->file('image')
            ->store('activities', 'public');
    } elseif ($request->filled('api_image')) {
        $imagePath = $request->api_image;
    }

    // ==========================================
    // 4. บันทึกภาพยนตร์
    // ==========================================
   Activity::create([
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

    return redirect()
        ->route('dashboard')
        ->with(
            'success',
            'ส่งข้อมูลภาพยนตร์สำเร็จ! กรุณารอแอดมินตรวจสอบอนุมัติครับ'
        );
}
    // 1. ฟังก์ชันดูหนังที่ตัวเองเสนอ
    public function myMovies()
    {
        $movies = Activity::where('user_id', auth()->id())->latest()->get();
        return view('activities.my_movies', compact('movies'));
    }

    // 2. ฟังก์ชันแก้ไข (ล็อกสิทธิ์)
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);

        // 🚨 ระบบล็อกสิทธิ์: ถ้าไม่ใช่แอดมิน + ไม่ใช่เจ้าของหนัง หรือ หนังอนุมัติไปแล้ว -> เตะออก
        if (auth()->user()->role !== 'admin') {
            if (
    $activity->user_id !== auth()->id()
    || $activity->status === 'approved'
) {
                return redirect()->route('my.movies')->with('error', '❌ คุณไม่มีสิทธิ์แก้ไข หรือภาพยนตร์ถูกอนุมัติไปแล้ว');
            }
        }

        $types = \App\Models\Type::orderBy('name')->get();

        return view('activities.edit', compact(
            'activity',
            'types'
        ));
    }

    // 3. ฟังก์ชันอัปเดตข้อมูล
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
            return redirect()->route('my.movies')->with('error', '❌ ไม่อนุญาตให้แก้ไขข้อมูล');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'review' => 'required|string',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|image|max:5120',
            'api_image' => 'nullable|string', // 🟢 เพิ่มการรองรับลิงก์รูปจาก API
        ]);
        
        // 🚨 ระบบเช็คหนังซ้ำสำหรับการแก้ไข (ต้องยกเว้น ID ของตัวเองด้วย)
        $isDuplicate = \App\Models\Activity::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                                           ->where('year', $request->year)
                                           ->where('id', '!=', $id)
                                           ->exists();

        if ($isDuplicate) {
            return back()->withInput()->withErrors(['name' => '❌ ไม่สามารถเปลี่ยนชื่อเป็นเรื่องนี้ได้ เพราะมีอยู่ในคลังแล้วครับ!']);
        }

        $updateData = [
            'name' => $request->name,
            'year' => $request->year,
            'review' => $request->review,
            'type_id' => $request->type_id,
        ];
// ถ้า User แก้หนังที่ถูกปฏิเสธ
// ให้ส่งกลับเข้าสู่ขั้นตอนตรวจสอบอีกครั้ง
if (auth()->user()->role !== 'admin' && $activity->status === 'rejected') {
    $updateData['status'] = 'pending';
    $updateData['is_approved'] = 0;
}

        // 🟢 อัปเดตรูปภาพ (ถ้าอัปโหลดใหม่ หรือมีลิงก์ API ส่งมาใหม่)
        if ($request->hasFile('image')) {
            $updateData['image'] = $request->file('image')->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $updateData['image'] = $request->api_image;
        }

        $activity->update($updateData);

        return redirect()->route('my.movies')->with('success', '✅ อัปเดตข้อมูลภาพยนตร์เรียบร้อยแล้ว');
    }

// 4. ฟังก์ชันลบภาพยนตร์ / ปัดตกรายการ
    public function destroy($id)
    {
        $movie = Activity::findOrFail($id);

        // ตรวจสอบสิทธิ์: อนุญาตเฉพาะ Admin หรือเจ้าของที่เสนอเรื่องนี้เข้ามา
        if (auth()->user()->role !== 'admin' && $movie->user_id !== auth()->id()) {
            return back()->with('error', '❌ คุณไม่มีสิทธิ์ลบรายการนี้');
        }

        // ลบไฟล์ภาพออกจาก Storage หากเป็นไฟล์ที่อัปโหลดเอง (ไม่ใช่ URL จาก TMDB)
        if ($movie->image && !\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://'])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($movie->image);
        }

        $movie->delete();

        return back()->with('success', '🗑️ ลบ/ปัดตกข้อมูลภาพยนตร์เรียบร้อยแล้ว');
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


}