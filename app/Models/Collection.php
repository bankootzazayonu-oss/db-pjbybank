<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = ['user_id', 'name', 'tier_labels'];

    protected $casts = [
        'tier_labels' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(CollectionItem::class);
    }
}
