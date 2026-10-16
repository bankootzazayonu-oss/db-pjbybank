<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use SoftDeletes;

    protected $table = 'platforms';
    protected $primaryKey = 'Platform_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = ['Platform_ID', 'Platform_Name', 'Logo'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Platform_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Platform_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/P(\d+)/', $last->Platform_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Platform_ID = 'P' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Platform_ID'] ?? null; }
    public function getNameAttribute() { return $this->attributes['Platform_Name'] ?? null; }
    public function setNameAttribute($val) { $this->attributes['Platform_Name'] = $val; }
    public function getLogoAttribute() { return $this->attributes['Logo'] ?? null; }
    public function setLogoAttribute($val) { $this->attributes['Logo'] = $val; }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_platform', 'Platform_ID', 'Movie_ID');
    }
}