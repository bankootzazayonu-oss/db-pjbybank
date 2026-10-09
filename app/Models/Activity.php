<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'original_title',
        'year',
        'review',
        'image',
        'user_id',
        'hours',
        'type_id',
        'director_id', 
        'is_approved',
        'tmdb_id',
        'status',
    ];
    
    // เชื่อมแบบ 1-to-Many
    public function user() { return $this->belongsTo(User::class); }
    public function type() { return $this->belongsTo(Type::class); }
    public function director() { return $this->belongsTo(Director::class); }

    // เชื่อมแบบ Many-to-Many (นักแสดง & แพลตฟอร์ม)
    public function actors() { return $this->belongsToMany(Actor::class); }
    public function platforms() { return $this->belongsToMany(Platform::class); }

    // เชื่อมกับตารางรีวิว
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest(); 
    }
}