<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $fillable = ['collection_id', 'activity_id', 'tier_rank'];

    // ไอเทมนี้ คือหนังเรื่องอะไร
    public function movie()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}