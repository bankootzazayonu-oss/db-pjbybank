<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $activityId)
    {
        // 1. ตรวจสอบข้อมูลที่ผู้ใช้กรอกมา
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string|max:1000',
        ]);

        // 2. บันทึกลงฐานข้อมูล
        Review::create([
            'user_id' => auth()->id(),
            'activity_id' => $activityId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_spoiler' => $request->has('is_spoiler') ? 1 : 0, // ถ้าติ๊กสปอยล์จะเป็น 1
        ]);

        return back()->with('success', '✅ บันทึกรีวิวและให้คะแนนภาพยนตร์เรียบร้อยแล้ว!');
    }
    public function storeReply(Request $request, $reviewId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        \App\Models\ReviewReply::create([
            'review_id' => $reviewId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'ตอบกลับความคิดเห็นเรียบร้อยแล้ว!');
    }
}
