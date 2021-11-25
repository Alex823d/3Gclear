<?php

namespace App\Models;


use Arr;
use Database\Factories\ActivityFactory;
use Database\Factories\CustomerFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $surname
 * @property string|null $phone_number
 * @property int|null $terms
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $status
 * @method static Builder|User filter($params = [])
 * @method static Builder|User whereAdvertisement($value)
 * @method static Builder|User whereCitizenshipId($value)
 * @method static Builder|User wherePassportNumber($value)
 * @method static Builder|User wherePersonalNumber($value)
 * @method static Builder|User wherePhoneNumber($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereSurname($value)
 * @method static Builder|User whereTerms($value)
 * @method static Builder|User whereVerifiedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-read int|null $events_count
 * @property-read string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|CompanyMember[] $ownCompanies
 * @property-read int|null $own_companies_count
 */
class User extends Authenticatable implements MustVerifyPhone, EventConnectionAble
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    

    /**
     * Status list.
     */
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'phone_number',
        'terms',
        'email',
        'password',
        'profile_photo_path',
        'provider',
        'provider_id',
        'status',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'full_name'
    ];

    
    
    
    

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * @param Builder $builder
     * @param array $params
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $params = [])
    {
        if (!empty($params['q'])) {
            $builder->where(function ($query) use ($params) {
                $query->orWhere('id', $params['q'])->orWhere('name', 'like', '%' . $params['q'] . '%')
                    ->orWhere('surname', 'like', '%' . $params['q'] . '%');
            });
        }
        if (!empty($params['email'])) {
            $builder->where('email', 'like', '%' . $params['email'] . '%');
        }
        if (!empty($params['name'])) {
            $builder->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (!empty($params['surname'])) {
            $builder->where('surname', 'like', '%' . $params['surname'] . '%');
        }
        if (!empty($params['phone_number'])) {
            $builder->where('phone_number', 'like', '%' . $params['phone_number'] . '%');
        }
        if (!empty($params['is_verify'])) {
            $builder->where('verified_at', $params['is_verify'] == 1 ? '=' : '!=', null);
        }
        if (!empty($params['date_from'])) {
            $builder->whereDate('created_at', '>=', $params['date_from']);
        }
        if (!empty($params['date_to'])) {
            $builder->whereDate('created_at', '<=', $params['date_to']);
        }


        return $builder;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return new CustomerFactory();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
            : $this->defaultProfilePhotoUrl();
    }

    

   
}
