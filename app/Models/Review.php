<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'activity_id', 'rating', 'comment', 'is_spoiler'];

    // 🟢 1. เพิ่มฟังก์ชันนี้เพื่อให้เชื่อมกับภาพยนตร์ (Activity) ได้
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    // 🟢 2. เพิ่มฟังก์ชันนี้เพื่อให้เชื่อมกับผู้ใช้ (User) ได้ (ถ้าคุณยังไม่ได้ใส่ไว้)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. เชื่อมกับการตอบกลับ (ถ้ามีแล้วก็ปล่อยไว้เหมือนเดิมครับ)
    public function replies()
    {
        return $this->hasMany(ReviewReply::class);
    }
}