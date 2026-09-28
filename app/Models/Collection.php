<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(CollectionItem::class); }
}