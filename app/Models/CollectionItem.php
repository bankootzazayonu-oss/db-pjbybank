<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    protected $fillable = ['collection_id', 'activity_id', 'tier_rank'];

    public function collection() { return $this->belongsTo(Collection::class); }
    public function activity() { return $this->belongsTo(Activity::class); }
}