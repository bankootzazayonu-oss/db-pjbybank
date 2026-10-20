<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected $table = 'users';
    protected $primaryKey = 'User_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'User_ID',
        'Username',
        'Email',
        'Password',
        'Role',
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'Password',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
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
            if (empty($model->User_ID)) {
                $last = static::orderByRaw('CAST(SUBSTR(User_ID, 2) AS UNSIGNED) DESC')->first();
                $num = 1;
                if ($last && preg_match('/U(\d+)/', $last->User_ID, $m)) {
                    $num = intval($m[1]) + 1;
                }
                $model->User_ID = 'U' . str_pad($num, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getIdAttribute() { return $this->User_ID; }
    public function getNameAttribute() { return $this->Username; }
    public function setNameAttribute($value) { $this->attributes['Username'] = $value; }
    public function getEmailAttribute() { return $this->attributes['Email'] ?? null; }
    public function setEmailAttribute($value) { $this->attributes['Email'] = $value; }
    public function getPasswordAttribute() { return $this->attributes['Password'] ?? null; }
    public function setPasswordAttribute($value) {
        $this->attributes['Password'] = !empty($value) && \Illuminate\Support\Facades\Hash::needsRehash($value) ? \Illuminate\Support\Facades\Hash::make($value) : $value;
    }
    public function getRoleAttribute() { return $this->attributes['Role'] ?? 'user'; }
    public function setRoleAttribute($value) { $this->attributes['Role'] = $value; }
    public function getCreatedAtAttribute() { return isset($this->attributes['CreatedAt']) ? $this->asDateTime($this->attributes['CreatedAt']) : null; }
    public function getUpdatedAtAttribute() { return isset($this->attributes['UpdatedAt']) ? $this->asDateTime($this->attributes['UpdatedAt']) : null; }

    public function initials(): string
    {
        $name = $this->Username ?? 'User';
        $initials = Str::initials($name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function activities() { return $this->hasMany(Activity::class, 'Submitted_By', 'User_ID'); }
    public function reviews() { return $this->hasMany(Review::class, 'User_ID', 'User_ID'); }
    public function replies() { return $this->hasMany(ReviewReply::class, 'User_ID', 'User_ID'); }
    public function collections() { return $this->hasMany(Collection::class, 'User_ID', 'User_ID'); }
    public function favoriteReviews() { return $this->belongsToMany(Review::class, 'favorite_reviews', 'User_ID', 'Review_ID'); }
    public function passkeys(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(\Laravel\Passkeys\Passkeys::passkeyModel(), 'user_id', 'User_ID'); }
    public function admin() { return $this->hasOne(Admin::class, 'Email', 'Email'); }
}
