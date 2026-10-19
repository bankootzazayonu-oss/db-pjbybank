<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Activity;

class CollectionController extends Controller
{

    public function index()
    {
        $collections = Collection::where('User_ID', auth()->id())->latest('CreatedAt')->get();
        return view('collections.index', compact('collections'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Collection::create([
            'User_ID' => auth()->id(),
            'Tier_Title' => $request->name
        ]);
        return back()->with('success', 'สร้างกระดานใหม่สำเร็จ');
    }

    public function show($id)
    {
        $collection = Collection::where('User_ID', auth()->id())->findOrFail($id);
        
        $items = $collection->items()->with('movie')->get()->groupBy('tier_rank');
        
        $allMovies = Activity::where('Approval_Status', 'approved')->orderBy('Title')->get();

        return view('collections.show', compact('collection', 'items', 'allMovies'));
    }

    public function addMovie(Request $request, $id)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,Movie_ID',
            'tier_rank' => 'required|in:S,A,B,C,D'
        ]);

        $collection = Collection::where('Tier_ID', $id)
            ->where('User_ID', auth()->id())
            ->firstOrFail();

        $exists = CollectionItem::where('Tier_ID', $collection->Tier_ID)
            ->where('Movie_ID', $request->activity_id)
            ->first();

        if ($exists) {
            return back()->with('error', 'ภาพยนตร์เรื่องนี้อยู่ในกระดานแล้ว');
        }

        CollectionItem::create([
            'Tier_ID' => $collection->Tier_ID,
            'Movie_ID' => $request->activity_id,
            'Tier_Level' => $request->tier_rank
        ]);

        return back()->with('success', 'เพิ่มภาพยนตร์ลงกระดานสำเร็จ');
    }

    public function updateRank(Request $request, $id)
    {
        $request->validate([
            'tier_rank' => 'required|in:S,A,B,C,D'
        ]);

        $parts = explode('_', $id);
        if (count($parts) === 2) {
            $item = CollectionItem::where('Tier_ID', $parts[0])->where('Movie_ID', $parts[1])->firstOrFail();
        } else {
            $item = CollectionItem::where('Movie_ID', $id)->firstOrFail();
        }

        Collection::where('Tier_ID', $item->Tier_ID)
            ->where('User_ID', auth()->id())
            ->firstOrFail();

        CollectionItem::where('Tier_ID', $item->Tier_ID)->where('Movie_ID', $item->Movie_ID)->update([
            'Tier_Level' => $request->tier_rank
        ]);

        return back()->with('success', 'เปลี่ยนระดับสำเร็จ');
    }

    public function destroyItem($id)
    {
        $parts = explode('_', $id);
        if (count($parts) === 2) {
            $item = CollectionItem::where('Tier_ID', $parts[0])->where('Movie_ID', $parts[1])->firstOrFail();
        } else {
            $item = CollectionItem::where('Movie_ID', $id)->firstOrFail();
        }

        Collection::where('Tier_ID', $item->Tier_ID)
            ->where('User_ID', auth()->id())
            ->firstOrFail();

        CollectionItem::where('Tier_ID', $item->Tier_ID)->where('Movie_ID', $item->Movie_ID)->delete();

        return back()->with('success', 'ลบภาพยนตร์ออกจากกระดานสำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ], [
            'name.required' => 'กรุณาระบุชื่อกระดาน'
        ]);
        
        $collection = Collection::findOrFail($id);

        if ($collection->User_ID !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขกระดานนี้');
        }

        $collection->update(['Tier_Title' => $request->name]);

        return back()->with('success', 'แก้ไขชื่อกระดานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        if ($collection->User_ID !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบกระดานนี้');
        }

        CollectionItem::where('Tier_ID', $collection->Tier_ID)->delete();
        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'ลบกระดานจัดอันดับเรียบร้อยแล้ว');
    }

    public function updateLabels(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);

        if ($collection->User_ID !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขกระดานนี้');
        }

        $collection->update([
            'Tier_Labels' => [
                'S' => $request->input('s_label', 'S'),
                'A' => $request->input('a_label', 'A'),
                'B' => $request->input('b_label', 'B'),
                'C' => $request->input('c_label', 'C'),
                'D' => $request->input('d_label', 'D'),
            ]
        ]);

        return back()->with('success', 'อัปเดตชื่อระดับเรียบร้อยแล้ว');
    }

    public function searchTmdb(Request $request)
    {
        $query = $request->input('query');
        if (!$query) return response()->json([]);

        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => env('TMDB_API_KEY'), 
            'query' => $query,
            'language' => 'th-TH',
            'page' => 1
        ]);

        return response()->json($response->json('results') ?? []);
    }

    public function storeFromTmdb(Request $request, $id)
    {
        $request->validate([
            'tmdb_id' => 'required',
            'tier_rank' => 'required|in:S,A,B,C,D',
        ]);

        $collection = Collection::findOrFail($id);
        if ($collection->User_ID !== auth()->id()) abort(403);

        $tmdbId = $request->tmdb_id;
        $movie = Activity::where('TMDB_ID', $tmdbId)->first();

        if (!$movie) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'th-TH'
            ]);

            if ($response->failed()) return back()->with('error', 'ไม่สามารถดึงข้อมูลจาก TMDB ได้');
            $tmdbData = $response->json();
            
            $movie = Activity::create([
                'Title' => $tmdbData['title'] ?? $tmdbData['original_title'],
                'Release_Year' => isset($tmdbData['release_date'])
                    ? intval(substr($tmdbData['release_date'], 0, 4))
                    : intval(date('Y')),
                'Description' => $tmdbData['overview'] ?? 'ไม่มีเรื่องย่อ',
                'Duration_Hours' => isset($tmdbData['runtime'])
                    ? round($tmdbData['runtime'] / 60, 1)
                    : 2,
                'Poster' => $tmdbData['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $tmdbData['poster_path']
                    : null,
                'Submitted_By' => auth()->id(),
                'Approval_Status' => 'approved',
                'Genre_ID' => null,
                'TMDB_ID' => $tmdbId,
            ]);
        }

        $exists = CollectionItem::where('Tier_ID', $collection->Tier_ID)->where('Movie_ID', $movie->Movie_ID)->exists();
        if ($exists) {
            return back()->with('error', 'ภาพยนตร์เรื่องนี้อยู่ในกระดานของคุณแล้ว');
        }

        CollectionItem::create([
            'Tier_ID' => $collection->Tier_ID,
            'Movie_ID' => $movie->Movie_ID,
            'Tier_Level' => $request->tier_rank
        ]);

        return back()->with('success', "นำเข้าภาพยนตร์ '{$movie->Title}' ลงกระดานเรียบร้อยแล้ว");
    }

    public function trash()
    {
        $collections = Collection::onlyTrashed()->where('User_ID', auth()->id())->latest('CreatedAt')->get();
        return view('collections.trash', compact('collections'));
    }

    public function restore($id)
    {
        $collection = Collection::onlyTrashed()->where('User_ID', auth()->id())->findOrFail($id);
        $collection->restore();
        return back()->with('success', 'กู้คืนกระดาน ' . $collection->Tier_Title . ' สำเร็จ!');
    }

    public function forceDelete($id)
    {
        $collection = Collection::onlyTrashed()->where('User_ID', auth()->id())->findOrFail($id);
        $collection->forceDelete();
        return back()->with('success', 'ลบกระดาน ' . $collection->Tier_Title . ' ถาวรสำเร็จ!');
    }
}