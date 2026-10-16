<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reviews';
    protected $primaryKey = 'Review_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = [
        'Review_ID',
        'User_ID',
        'Movie_ID',
        'Review_Text',
        'Score',
        'Spoiler',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Review_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Review_ID, 4) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/REV(\d+)/', $last->Review_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Review_ID = 'REV' . str_pad($num, 7, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Review_ID'] ?? null; }
    public function getUserIdAttribute() { return $this->attributes['User_ID'] ?? null; }
    public function setUserIdAttribute($val) { $this->attributes['User_ID'] = $val; }
    public function getActivityIdAttribute() { return $this->attributes['Movie_ID'] ?? null; }
    public function setActivityIdAttribute($val) { $this->attributes['Movie_ID'] = $val; }
    public function getRatingAttribute() { return $this->attributes['Score'] ?? null; }
    public function setRatingAttribute($val) { $this->attributes['Score'] = $val; }
    public function getCommentAttribute() { return $this->attributes['Review_Text'] ?? null; }
    public function setCommentAttribute($val) { $this->attributes['Review_Text'] = $val; }
    public function getIsSpoilerAttribute() { return $this->attributes['Spoiler'] ?? null; }
    public function setIsSpoilerAttribute($val) { $this->attributes['Spoiler'] = $val; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'Movie_ID', 'Movie_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }

    public function replies()
    {
        return $this->hasMany(ReviewReply::class, 'Review_ID', 'Review_ID');
    }

    public function reports()
    {
        return $this->hasMany(CommentReport::class, 'Review_ID', 'Review_ID');
    }
}