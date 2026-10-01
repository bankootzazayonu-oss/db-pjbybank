<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    // 🟢 1. เพิ่ม 'tier_labels' เข้าไปใน $fillable
    protected $fillable = ['user_id', 'name', 'tier_labels'];

    protected $casts = [
        'tier_labels' => 'array',
    ];
    // 1 กระดาน มีหนังหลายเรื่อง
    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }
}
