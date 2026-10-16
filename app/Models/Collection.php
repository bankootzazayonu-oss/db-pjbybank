<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'collections';
    protected $primaryKey = 'Tier_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = ['Tier_ID', 'User_ID', 'Tier_Title', 'Tier_Labels'];

    protected $casts = [
        'Tier_Labels' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Tier_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Tier_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/T(\d+)/', $last->Tier_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Tier_ID = 'T' . str_pad($num, 9, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->attributes['Tier_ID'] ?? null; }
    public function getUserIdAttribute() { return $this->attributes['User_ID'] ?? null; }
    public function setUserIdAttribute($val) { $this->attributes['User_ID'] = $val; }
    public function getNameAttribute() { return $this->attributes['Tier_Title'] ?? null; }
    public function setNameAttribute($val) { $this->attributes['Tier_Title'] = $val; }
    public function getTitleAttribute() { return $this->attributes['Tier_Title'] ?? null; }
    public function setTitleAttribute($val) { $this->attributes['Tier_Title'] = $val; }
    public function getTierLabelsAttribute() {
        $labels = isset($this->attributes['Tier_Labels']) && is_string($this->attributes['Tier_Labels']) ? json_decode($this->attributes['Tier_Labels'], true) : ($this->attributes['Tier_Labels'] ?? []);
        return array_merge([
            'S' => 'S',
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D'
        ], is_array($labels) ? $labels : []);
    }
    public function setTierLabelsAttribute($val) { $this->attributes['Tier_Labels'] = is_array($val) ? json_encode($val) : $val; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID', 'User_ID');
    }

    public function items()
    {
        return $this->hasMany(CollectionItem::class, 'Tier_ID', 'Tier_ID');
    }
}
