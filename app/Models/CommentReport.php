<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    use HasFactory;

    // อนุญาตให้บันทึกข้อมูลลง 3 คอลัมน์นี้
    protected $fillable = ['review_id', 'user_id', 'reason'];

    // ความสัมพันธ์: 1 รีพอร์ต เป็นของ 1 คอมเมนต์(รีวิว)
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // ความสัมพันธ์: 1 รีพอร์ต แจ้งโดย 1 ผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}