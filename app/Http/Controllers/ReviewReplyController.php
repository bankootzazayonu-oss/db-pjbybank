<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ReviewReply;

class ReviewReplyController extends Controller
{
    public function store(Request $request, $reviewId)
    {
        $request->validate(['message' => 'required|string|max:500']);
        ReviewReply::create([
            'Review_ID' => $reviewId,
            'User_ID' => auth()->id(),
            'Message' => $request->message,
        ]);
        return back()->with('success', 'ตอบกลับความเห็นเรียบร้อยแล้ว!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        
        $reply = ReviewReply::findOrFail($id);

        if ($reply->User_ID !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อความนี้');
        }

        $reply->update(['Message' => $request->message]);

        return back()->with('success', ' แก้ไขการตอบกลับเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $reply = ReviewReply::findOrFail($id);

        if ($reply->User_ID !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบข้อความนี้');
        }

        $reply->delete();

        return back()->with('success', '️ ลบการตอบกลับเรียบร้อยแล้ว');
    }
}
