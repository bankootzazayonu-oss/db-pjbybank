<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'admins';
    protected $primaryKey = 'Admin_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    const DELETED_AT = 'DeletedAt';

    protected $fillable = [
        'Admin_ID',
        'Username',
        'Email',
        'Password',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'Password',
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'Password' => 'hashed',
            'password' => 'hashed',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'Password';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->Admin_ID)) {
                $last = static::withTrashed()->orderByRaw('CAST(SUBSTR(Admin_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/A(\d+)/', $last->Admin_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->Admin_ID = 'A' . str_pad($num, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->Admin_ID; }
    public function getNameAttribute() { return $this->Username; }
    public function setNameAttribute($value) { $this->attributes['Username'] = $value; }
    public function getEmailAttribute() { return $this->attributes['Email'] ?? null; }
    public function setEmailAttribute($value) { $this->attributes['Email'] = $value; }
    public function getPasswordAttribute() { return $this->attributes['Password'] ?? null; }
    public function setPasswordAttribute($value) {
        $this->attributes['Password'] = !empty($value) && Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
    public function getRoleAttribute() { return 'admin'; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function initials(): string
    {
        $name = $this->Username ?? 'Admin';
        $initials = Str::initials($name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1) . Str::substr($initials, -1)
            : $initials;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Email', 'Email');
    }
}
