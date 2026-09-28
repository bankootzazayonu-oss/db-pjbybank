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
}
