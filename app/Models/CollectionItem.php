<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    public $timestamps = false;
    protected $table = 'collection_items';
    protected $primaryKey = ['Tier_ID', 'Movie_ID'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['Tier_ID', 'Movie_ID', 'Tier_Level'];

    public function getIdAttribute() { return $this->Tier_ID . '_' . $this->Movie_ID; }
    public function getCollectionIdAttribute() { return $this->Tier_ID; }
    public function setCollectionIdAttribute($val) { $this->attributes['Tier_ID'] = $val; }
    public function getActivityIdAttribute() { return $this->Movie_ID; }
    public function setActivityIdAttribute($val) { $this->attributes['Movie_ID'] = $val; }
    public function getTierRankAttribute() { return $this->Tier_Level; }
    public function setTierRankAttribute($val) { $this->attributes['Tier_Level'] = $val; }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'Tier_ID', 'Tier_ID');
    }

    public function movie()
    {
        return $this->belongsTo(Activity::class, 'Movie_ID', 'Movie_ID');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'Movie_ID', 'Movie_ID');
    }
}