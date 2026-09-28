<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['user_id', 'name'];

    // 1 กระดาน มีหนังหลายเรื่อง
    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }
}