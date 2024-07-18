<?php

namespace App\Models;

use App\Enums\Gender;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use SwitchTimezoneTrait;
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
        'timezone',
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
            fn(): ?string => isset($this->gender)
                ? Gender::valueForKey($this->gender)
                : null
        );
    }

    /**
     * @return HasMany
     */
    public function socials(): HasMany
    {
        return $this->hasMany(Social::class);
    }

    /**
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * @return HasMany
     */
    public function identifications(): HasMany
    {
        return $this->hasMany(Identification::class);
    }

    /**
     * @return MorphMany
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(ProductReview::class, 'reviewable');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'remember_token' => 'hashed',
        ];
    }

    /**
     * @return Attribute
     */
    protected function emailVerifiedAt(): Attribute
    {
        return Attribute::get(
            function (?string $email_verified_at): ?string {
                $user = auth()->user();

                return $user && $email_verified_at
                    ? Carbon::make($email_verified_at)
                        ->timezone($user->timezone)
                        ->format(config('app.timezone-format.long'))
                    : $email_verified_at;
            }
        );
    }
}
