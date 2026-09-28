<?php

namespace App\Http\Controllers\Admin;

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
            
            $response = Http::get("https://api.themoviedb.org/3/search/movie", [
                'api_key' => $apiKey,
                'query' => $query,
                'language' => 'th-TH',
            ]);

            if ($response->successful()) {
                $movies = $response->json()['results'];
            }
        }

        return view('admin.movies.search', compact('movies', 'query'));
    }

    // ฟังก์ชันใหม่สำหรับบันทึกลงฐานข้อมูล
    public function import(Request $request)
    {
        // 1. ระบบเช็กหนังซ้ำ (ตามที่อาจารย์สั่ง)
        $exists = Activity::where('name', $request->title)->where('year', $request->year)->first();
        if ($exists) {
            return back()->with('error', '⚠️ หนังเรื่อง "' . $request->title . '" มีอยู่ในระบบแล้ว!');
        }

        // 2. บันทึกลงฐานข้อมูล
        Activity::create([
            'name' => $request->title,
            'year' => $request->year,
            'review' => $request->overview,
            'image' => $request->poster_path ? 'https://image.tmdb.org/t/p/w500' . $request->poster_path : null,
            'hours' => 2.0, // ใส่ค่าเริ่มต้นไปก่อน (แอดมินค่อยไปแก้ทีหลังได้)
            'category' => 'รอระบุหมวดหมู่', // ใส่ค่าเริ่มต้น
            'is_approved' => 1,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', '✅ นำเข้า "' . $request->title . '" เข้าสู่คลังภาพยนตร์สำเร็จ!');
    }
}