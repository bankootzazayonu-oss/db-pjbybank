<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentReport extends Model
{
    use SoftDeletes;

    protected $table = 'comment_reports';
    protected $primaryKey = 'Report_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = ['Report_ID', 'Review_ID', 'User_ID', 'Reason'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Report_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Report_ID, 4) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/RPT(\d+)/', $last->Report_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Report_ID = 'RPT' . str_pad($num, 7, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Report_ID'] ?? null; }
    public function getReviewIdAttribute() { return $this->attributes['Review_ID'] ?? null; }
    public function setReviewIdAttribute($val) { $this->attributes['Review_ID'] = $val; }
    public function getUserIdAttribute() { return $this->attributes['User_ID'] ?? null; }
    public function setUserIdAttribute($val) { $this->attributes['User_ID'] = $val; }
    public function getReasonAttribute() { return $this->attributes['Reason'] ?? null; }
    public function setReasonAttribute($val) { $this->attributes['Reason'] = $val; }

    public function review()
    {
        return $this->belongsTo(Review::class, 'Review_ID', 'Review_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }
}
