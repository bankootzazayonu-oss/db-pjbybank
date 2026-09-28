<?php
namespace App\Http\Controllers;

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
}