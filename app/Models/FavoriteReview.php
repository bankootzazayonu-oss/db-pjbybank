<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteReview extends Model
{
    public $timestamps = false;
    protected $table = 'favorite_reviews';
    protected $primaryKey = ['User_ID', 'Review_ID'];
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'Saved_At';

    protected $fillable = ['User_ID', 'Review_ID', 'Saved_At'];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'Review_ID', 'Review_ID');
    }
}
