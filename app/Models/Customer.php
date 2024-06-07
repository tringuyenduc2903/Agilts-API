<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'birthday',
        'gender',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $appends = [
        'gender_preview',
    ];

    /**
     * @return Attribute
     */
    public function genderPreview(): Attribute
    {
        return Attribute::get(
            fn(): ?string => isset($this->gender) ? Gender::valueForKey($this->gender) : null
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime:d-m-Y H:i:s',
            'phone_number_verified_at' => 'datetime:d-m-Y H:i:s',
            'two_factor_confirmed_at' => 'datetime:d-m-Y H:i:s',
            'deleted_at' => 'datetime:d-m-Y H:i:s',
            'created_at' => 'datetime:d-m-Y H:i:s',
            'updated_at' => 'datetime:d-m-Y H:i:s',
            'password' => 'hashed',
        ];
    }
}
