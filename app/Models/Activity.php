<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'activities';
    protected $primaryKey = 'Movie_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = [
        'Movie_ID',
        'Title',
        'Original_Title',
        'Release_Year',
        'Duration_Hours',
        'TMDB_ID',
        'Genre_ID',
        'Submitted_By',
        'Approval_Status',
        'Approved_By',
        'Poster',
        'Description',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Movie_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Movie_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/M(\d+)/', $last->Movie_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Movie_ID = 'M' . str_pad($num, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Movie_ID'] ?? null; }
    public function getNameAttribute() { return $this->attributes['Title'] ?? null; }
    public function setNameAttribute($val) { $this->attributes['Title'] = $val; }
    public function getYearAttribute() { return $this->attributes['Release_Year'] ?? null; }
    public function setYearAttribute($val) { $this->attributes['Release_Year'] = $val; }
    public function getHoursAttribute() { return $this->attributes['Duration_Hours'] ?? null; }
    public function setHoursAttribute($val) { $this->attributes['Duration_Hours'] = $val; }
    public function getImageAttribute() { return $this->attributes['Poster'] ?? null; }
    public function setImageAttribute($val) { $this->attributes['Poster'] = $val; }
    public function getReviewAttribute() { return $this->attributes['Description'] ?? null; }
    public function setReviewAttribute($val) { $this->attributes['Description'] = $val; }
    public function getUserIdAttribute() { return $this->attributes['Submitted_By'] ?? null; }
    public function setUserIdAttribute($val) { $this->attributes['Submitted_By'] = $val; }
    public function getTypeIdAttribute() { return $this->attributes['Genre_ID'] ?? null; }
    public function setTypeIdAttribute($val) { $this->attributes['Genre_ID'] = $val; }
    public function getStatusAttribute() { return $this->attributes['Approval_Status'] ?? null; }
    public function setStatusAttribute($val) { $this->attributes['Approval_Status'] = $val; }
    public function getIsApprovedAttribute() { return ($this->attributes['Approval_Status'] ?? '') === 'approved'; }
    public function setIsApprovedAttribute($val) { $this->attributes['Approval_Status'] = $val ? 'approved' : 'pending'; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function user()
    {
        return $this->belongsTo(User::class, 'Submitted_By', 'User_ID');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'Genre_ID', 'Genre_ID');
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'activity_platform', 'Movie_ID', 'Platform_ID');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'Movie_ID', 'Movie_ID')->latest('CreatedAt');
    }

    public function collectionItems()
    {
        return $this->hasMany(CollectionItem::class, 'Movie_ID', 'Movie_ID');
    }

    public function approver()
    {
        return $this->belongsTo(Admin::class, 'Approved_By', 'Admin_ID');
    }
}