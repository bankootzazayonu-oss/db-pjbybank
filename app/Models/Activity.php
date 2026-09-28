<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
   protected $fillable = [
        'name',
        'year',
        'review',
        'image',
        'user_id',
        'hours',
        'type_id', // 👈 เปลี่ยนจาก category เป็น type_id
        'is_approved',
    ];
    
    // เชื่อมแบบ 1-to-Many
    public function user() { return $this->belongsTo(User::class); }
    public function type() { return $this->belongsTo(Type::class); }
    public function director() { return $this->belongsTo(Director::class); }
   

    // เชื่อมแบบ Many-to-Many (นักแสดง & แพลตฟอร์ม)
    public function actors() { return $this->belongsToMany(Actor::class); }
    public function platforms() { return $this->belongsToMany(Platform::class); }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); // ดึงรีวิวใหม่ล่าสุดขึ้นก่อน
    }
}