<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['name', 'logo'];
    public function activities() { return $this->belongsToMany(Activity::class); }
}