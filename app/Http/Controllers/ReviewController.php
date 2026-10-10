<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\CommentReport;

class ReviewController extends Controller
{

    public function store(Request $request, $activityId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'activity_id' => $activityId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_spoiler' => $request->has('is_spoiler') ? 1 : 0,
        ]);

        return back()->with('success', '✅ บันทึกรีวิวและให้คะแนนภาพยนตร์เรียบร้อยแล้ว!');
    }


    public function storeReply(Request $request, $reviewId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ReviewReply::create([
            'review_id' => $reviewId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'ตอบกลับความคิดเห็นเรียบร้อยแล้ว!');
    }


    public function report($reviewId)
    {

        $alreadyReported = CommentReport::where('review_id', $reviewId)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', '⚠️ คุณได้ส่งรายงานคอมเมนต์นี้ไปก่อนหน้านี้แล้ว');
        }

        CommentReport::create([
            'review_id' => $reviewId,
            'user_id' => auth()->id(),
            'reason' => 'ผู้ใช้แจ้งว่ามีเนื้อหาไม่เหมาะสม หรือสแปม',
        ]);

        return back()->with('success', '🚩 รายงานคอมเมนต์ไปยังผู้ดูแลระบบเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:1000']);
        
        $review = Review::findOrFail($id);


        if ($review->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขคอมเมนต์นี้');
        }

        $review->update(['comment' => $request->comment]);

        return back()->with('success', '✅ แก้ไขคอมเมนต์เรียบร้อยแล้ว');
    }


    public function userDestroy($id)
    {
        $review = Review::findOrFail($id);


        if ($review->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'คุณไม่มีสิทธิ์ลบคอมเมนต์นี้');
        }


        CommentReport::where('review_id', $review->id)->delete();
        $review->delete();

        return back()->with('success', '🗑️ ลบคอมเมนต์ของคุณเรียบร้อยแล้ว');
    }




    public function adminReports()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $reports = CommentReport::with(['review.user', 'review.activity', 'user'])->latest()->get();
        
        return view('admin.reports', compact('reports'));
    }


    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $review = Review::findOrFail($id);
        

        CommentReport::where('review_id', $review->id)->delete();
        $review->delete();

        return back()->with('success', '🗑️ ลบคอมเมนต์ที่ไม่เหมาะสมออกจากระบบเรียบร้อยแล้ว');
    }


    public function dismissReport($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $report = CommentReport::findOrFail($id);
        $report->delete();

        return back()->with('success', '✅ ปัดตกรีพอร์ตเรียบร้อยแล้ว (คอมเมนต์ยังคงอยู่)');
    }
}