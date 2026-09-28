<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function show($id)
    {
        // เพิ่ม with('reviews.user') เพื่อดึงข้อมูลคนที่รีวิวมาด้วย
        $movie = Activity::with('reviews.user', 'reviews.replies.user')->findOrFail($id);
        
        return view('activities.show', compact('movie'));
    }

    public function leaderboard()
    {
        // ดึงหนังที่มีการอนุมัติแล้ว พร้อมคำนวณคะแนนเฉลี่ยและจำนวนรีวิว
        $topMovies = Activity::where('is_approved', 1) // ใช้ 1 ชัวร์กว่า true
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews') // 👈 เปลี่ยนจาก having เป็น has() แก้จอแดงได้ 100%
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
        return view('activities.create');
    }

    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล (ปรับให้รับไฟล์ได้สูงสุด 5MB และรับภาพได้ทุกนามสกุล)
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
            'review' => 'required|string',
            'image' => 'nullable|image|max:5120', 
        ]);

        // 2. จัดการอัปโหลดไฟล์
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activities', 'public');
        }

        // 3. บันทึกข้อมูลลงฐานข้อมูล
       // 3. บันทึกข้อมูลลงฐานข้อมูล
        $newMovie = Activity::create([
            'name' => $request->name,
            'year' => $request->year,
            'review' => $request->review,
            'image' => $imagePath,
            'user_id' => auth()->id(), 
            'hours' => 0, 
            'type_id' => null, // 👈 เปลี่ยนบรรทัดนี้ ให้เป็นค่าว่างรอแอดมินมาจัดหมวดหมู่
            'is_approved' => 0, 
        ]);

        // (ถ้ามีบรรทัด dd() อยู่ ลบทิ้งได้เลยครับ จะได้เด้งกลับไปหน้าแรกสวยๆ)
        

        return redirect()->route('dashboard')->with('success', 'ส่งข้อมูลภาพยนตร์สำเร็จ! กรุณารอแอดมินตรวจสอบอนุมัติครับ');
    }
}