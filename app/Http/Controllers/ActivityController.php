<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Validation\Rule;

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
        $topMovies = Activity::where('is_approved', 1)
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
        // ดึงหนังที่รออนุมัติ (สถานะ 0 หรือ false)
        $movies = Activity::whereIn('is_approved', [0, false])->latest()->get();
        
        // ส่งข้อมูลไปที่หน้า admin/pending.blade.php
        return view('admin.pending', compact('movies'));
    }

    public function approve($id)
    {
        // แอดมินกดอนุมัติ (อัปเดตเป็น 1)
        $movie = Activity::findOrFail($id);
        $movie->update(['is_approved' => 1]); 
        return back()->with('success', '✅ อนุมัติภาพยนตร์เรื่อง ' . $movie->name . ' เรียบร้อยแล้ว');
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
            'year' => 'required|integer',
            'review' => 'required|string',
            'type_id' => 'required|exists:types,id',
            'image' => 'nullable|image|max:5120', 
            'api_image' => 'nullable|string', // 🟢 เพิ่มการรองรับลิงก์รูปจาก API
        ]);

        // 🚨 ระบบเช็คหนังซ้ำ: เช็กชื่อหนัง (ไม่สนพิมพ์เล็ก-ใหญ่) และ ปีที่ฉาย
        $isDuplicate = \App\Models\Activity::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                                           ->where('year', $request->year)
                                           ->exists();
        
        if ($isDuplicate) {
            return back()->withInput()->withErrors(['name' => '❌ ภาพยนตร์เรื่องนี้ (ปี '.$request->year.') มีอยู่ในคลังหรือกำลังรอตรวจสอบแล้วครับ!']);
        }

        // 2. จัดการอัปโหลดไฟล์ หรือ รูปจาก API
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $imagePath = $request->api_image; // 🟢 ใช้รูปลิงก์ TMDB ถ้าส่งมา
        }

        // 3. บันทึกข้อมูลลงฐานข้อมูล
        Activity::create([
            'name' => $request->name,
            'year' => $request->year,
            'review' => $request->review,
            'image' => $imagePath,
            'user_id' => auth()->id(), 
            'hours' => 0, 
            'type_id' => $request->type_id, 
            'category' => '-', 
            'is_approved' => 0, 
        ]);

        return redirect()->route('dashboard')->with('success', 'ส่งข้อมูลภาพยนตร์สำเร็จ! กรุณารอแอดมินตรวจสอบอนุมัติครับ');
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
            if ($activity->user_id !== auth()->id() || $activity->is_approved == 1) {
                return redirect()->route('my.movies')->with('error', '❌ คุณไม่มีสิทธิ์แก้ไข หรือภาพยนตร์ถูกอนุมัติไปแล้ว');
            }
        }

        $types = \App\Models\Type::orderBy('name')->get();
        return view('activities.edit', compact('activity', 'types'));
    }

    // 3. ฟังก์ชันอัปเดตข้อมูล
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        if (auth()->user()->role !== 'admin' && ($activity->user_id !== auth()->id() || $activity->is_approved == 1)) {
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

        // 🟢 อัปเดตรูปภาพ (ถ้าอัปโหลดใหม่ หรือมีลิงก์ API ส่งมาใหม่)
        if ($request->hasFile('image')) {
            $updateData['image'] = $request->file('image')->store('activities', 'public');
        } elseif ($request->filled('api_image')) {
            $updateData['image'] = $request->api_image;
        }

        $activity->update($updateData);

        return redirect()->route('my.movies')->with('success', '✅ อัปเดตข้อมูลภาพยนตร์เรียบร้อยแล้ว');
    }
}