<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;

    protected $table = 'types';
    protected $primaryKey = 'Genre_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = ['Genre_ID', 'Genre_Name'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Genre_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Genre_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/G(\d+)/', $last->Genre_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Genre_ID = 'G' . str_pad($num, 2, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Genre_ID'] ?? null; }
    public function getNameAttribute() { return $this->attributes['Genre_Name'] ?? null; }
    public function setNameAttribute($val) { $this->attributes['Genre_Name'] = $val; }

    public function activities() { return $this->hasMany(Activity::class, 'Genre_ID', 'Genre_ID'); }
}