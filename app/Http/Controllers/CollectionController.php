<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Activity;

class CollectionController extends Controller
{
    // 1. หน้าแสดงกระดานทั้งหมดของผู้ใช้
    public function index()
    {
        $collections = Collection::where('user_id', auth()->id())->latest()->get();
        return view('collections.index', compact('collections'));
    }

    // 2. สร้างกระดานใหม่
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Collection::create([
            'user_id' => auth()->id(),
            'name' => $request->name
        ]);
        return back()->with('success', '✅ สร้างกระดานใหม่สำเร็จ!');
    }

    // 3. หน้าแสดง Tier List ของกระดานนั้นๆ
    public function show($id)
    {
        $collection = Collection::where('user_id', auth()->id())->findOrFail($id);
        
        // จัดกลุ่มหนังตามระดับ (S, A, B, C, D)
        $items = $collection->items()->with('movie')->get()->groupBy('tier_rank');
        
        // ดึงรายชื่อหนังทั้งหมดที่อนุมัติแล้วมาเป็นตัวเลือกให้กดเพิ่มเข้ากระดาน
        $allMovies = Activity::where('is_approved', 1)->orderBy('name')->get();

        return view('collections.show', compact('collection', 'items', 'allMovies'));
    }

    // 4. เพิ่มหนังเข้ากระดาน
    public function addMovie(Request $request, $id)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'tier_rank' => 'required|in:S,A,B,C,D'
        ]);

        $exists = CollectionItem::where('collection_id', $id)->where('activity_id', $request->activity_id)->first();
        if ($exists) {
            return back()->with('error', '❌ หนังเรื่องนี้อยู่ในกระดานแล้ว!');
        }

        CollectionItem::create([
            'collection_id' => $id,
            'activity_id' => $request->activity_id,
            'tier_rank' => $request->tier_rank
        ]);
        return back()->with('success', '✅ เพิ่มหนังลงกระดานสำเร็จ!');
    }

    // 5. อัปเดตระดับ (ย้าย Tier)
    public function updateRank(Request $request, $id)
    {
        $item = CollectionItem::findOrFail($id);
        $item->update(['tier_rank' => $request->tier_rank]);
        return back()->with('success', '🔄 เปลี่ยนระดับสำเร็จ!');
    }

    // 6. ลบหนังออกจากกระดาน
    public function destroyItem($id)
    {
        $item = CollectionItem::findOrFail($id);
        $item->delete();
        return back()->with('success', '🗑️ ลบหนังออกจากกระดานสำเร็จ!');
    }

    // 🟢 User กดบันทึกแก้ไขชื่อกระดาน
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ], [
            'name.required' => 'กรุณาระบุชื่อกระดาน'
        ]);
        
        $collection = \App\Models\Collection::findOrFail($id);

        // เช็กสิทธิ์: ป้องกันคนอื่นเอา ID มาแอบแก้กระดานของเรา
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขกระดานนี้');
        }

        $collection->update(['name' => $request->name]);

        return back()->with('success', '✅ แก้ไขชื่อกระดานเรียบร้อยแล้ว');
    }

    // 🔴 User กดลบกระดานทิ้ง
    public function destroy($id)
    {
        $collection = \App\Models\Collection::findOrFail($id);

        // เช็กสิทธิ์: ต้องเป็นเจ้าของเท่านั้น
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบกระดานนี้');
        }

        // 🛡️ ป้องกันข้อมูลขยะ: ลบหนังที่ถูกจัดอันดับอยู่ในกระดานนี้ทิ้งก่อน
        \App\Models\CollectionItem::where('collection_id', $collection->id)->delete();
        
        // จากนั้นถึงลบตัวกระดานหลัก
        $collection->delete();

        return redirect()->route('collections.index')->with('success', '🗑️ ลบกระดานจัดอันดับเรียบร้อยแล้ว');
    }

    // 🟢 User กดบันทึกชื่อระดับ (Tier)
    public function updateLabels(Request $request, $id)
    {
        $collection = \App\Models\Collection::findOrFail($id);

        if ($collection->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขกระดานนี้');
        }

        // เซฟข้อมูลเป็น Array (Laravel จะแปลงเป็น JSON ลง DB ให้เอง)
        $collection->update([
            'tier_labels' => [
                'S' => $request->input('s_label', 'S'),
                'A' => $request->input('a_label', 'A'),
                'B' => $request->input('b_label', 'B'),
                'C' => $request->input('c_label', 'C'),
                'D' => $request->input('d_label', 'D'),
            ]
        ]);

        return back()->with('success', '✨ อัปเดตชื่อระดับเรียบร้อยแล้ว!');
    }

    // 🟢 1. API ค้นหาหนังจาก TMDB ส่งกลับเป็น JSON ให้หน้าเว็บ (Live Search)
    public function searchTmdb(Request $request)
    {
        $query = $request->input('query');
        if (!$query) return response()->json([]);

        // ยิง API ไป TMDB สดๆ
        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => env('TMDB_API_KEY'), // มั่นใจว่าในไฟล์ .env มี TMDB_API_KEY แล้ว
            'query' => $query,
            'language' => 'th-TH',
            'page' => 1
        ]);

        return response()->json($response->json('results') ?? []);
    }

    // 🟢 2. นำเข้าหนังจาก TMDB และเพิ่มลงกระดานทันที
    public function storeFromTmdb(Request $request, $id)
    {
        $request->validate([
            'tmdb_id' => 'required',
            'tier_rank' => 'required|in:S,A,B,C,D',
        ]);

        $collection = \App\Models\Collection::findOrFail($id);
        if ($collection->user_id !== auth()->id()) abort(403);

        $tmdbId = $request->tmdb_id;
        
        // 1. เช็กว่าหนังเรื่องนี้เคยถูกดึงเข้ามาในระบบ (ตาราง activities) หรือยัง?
        // *หมายเหตุ: สมมติว่าตาราง Activity ของคุณมีคอลัมน์ tmdb_id เอาไว้เก็บ ID อ้างอิง
        $movie = Activity::where('tmdb_id', $tmdbId)->first();

        // 2. ถ้ายังไม่มี ให้ดึงข้อมูลรายละเอียดจาก TMDB มาสร้างหนังเรื่องใหม่ในระบบเลย!
        if (!$movie) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'th-TH'
            ]);

            if ($response->failed()) return back()->with('error', '❌ ไม่สามารถดึงข้อมูลจาก TMDB ได้');
            $tmdbData = $response->json();
            
            $movie = Activity::create([
                'name' => $tmdbData['title'] ?? $tmdbData['original_title'],
                'year' => isset($tmdbData['release_date']) ? substr($tmdbData['release_date'], 0, 4) : date('Y'),
                'review' => $tmdbData['overview'] ?? 'ไม่มีเรื่องย่อ',
                'hours' => isset($tmdbData['runtime']) ? round($tmdbData['runtime'] / 60, 1) : 2,
                'image' => $tmdbData['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $tmdbData['poster_path'] : null,
                'user_id' => auth()->id(), 
                'is_approved' => 1, // ✅ อนุมัติให้อัตโนมัติ เพราะมาจาก TMDB โดยตรง
                'type_id' => null,  // อาจจะเว้นว่างหมวดหมู่ไว้ก่อน
                'tmdb_id' => $tmdbId // บันทึก TMDB ID ไว้ คราวหน้าจะได้ไม่ต้องดึงซ้ำ
            ]);
        }

        // 3. เพิ่มหนังลงในกระดาน
        $exists = CollectionItem::where('collection_id', $collection->id)->where('activity_id', $movie->id)->exists();
        if ($exists) {
            return back()->with('error', '⚠️ หนังเรื่องนี้อยู่ในกระดานของคุณแล้ว!');
        }

        CollectionItem::create([
            'collection_id' => $collection->id,
            'activity_id' => $movie->id,
            'tier_rank' => $request->tier_rank
        ]);

        return back()->with('success', "✨ นำเข้าหนัง '{$movie->name}' ลงกระดานเรียบร้อย!");
    }
}