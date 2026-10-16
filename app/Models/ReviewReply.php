<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'review_replies';
    protected $primaryKey = 'Reply_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = ['Reply_ID', 'Review_ID', 'User_ID', 'Message'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Reply_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Reply_ID, 4) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/REP(\d+)/', $last->Reply_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Reply_ID = 'REP' . str_pad($num, 7, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Reply_ID'] ?? null; }
    public function getReviewIdAttribute() { return $this->attributes['Review_ID'] ?? null; }
    public function setReviewIdAttribute($val) { $this->attributes['Review_ID'] = $val; }
    public function getUserIdAttribute() { return $this->attributes['User_ID'] ?? null; }
    public function setUserIdAttribute($val) { $this->attributes['User_ID'] = $val; }
    public function getReplyAttribute() { return $this->attributes['Message'] ?? null; }
    public function setReplyAttribute($val) { $this->attributes['Message'] = $val; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'Review_ID', 'Review_ID');
    }
}