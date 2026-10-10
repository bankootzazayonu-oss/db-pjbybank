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
            'review_id' => $reviewId,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        return back()->with('success', 'ตอบกลับความเห็นเรียบร้อยแล้ว!');
    }


    public function update(Request $request, $id)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        
        $reply = \App\Models\ReviewReply::findOrFail($id);


        if ($reply->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อความนี้');
        }

        $reply->update(['message' => $request->message]);

        return back()->with('success', ' แก้ไขการตอบกลับเรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        $reply = \App\Models\ReviewReply::findOrFail($id);

        if ($reply->user_id !== auth()->id()) {
            abort(403, 'คุณไม่มีสิทธิ์ลบข้อความนี้');
        }

        $reply->delete();

        return back()->with('success', '️ ลบการตอบกลับเรียบร้อยแล้ว');
    }
}
