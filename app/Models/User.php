<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,HasUuids;

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->generateotpCode();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

     public function getJWTIdentifier()
     {
         return $this->getKey();
     }

     public function getJWTCustomClaims()
    {
        return [];
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



    public function Role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function generateotpCode(){
        do {
            $randomNumber = random_int(100000, 999999);
            $checkotpCode = otpCode::where('otp', $randomNumber)->first();
        }while ($checkotpCode);

        $now = Carbon::now();

        $otp_code = otpCode:: updateOrCreate(
            ['user_id' => $this->id],
            ['otp' => $randomNumber, 'valid_until' => $now->addMinutes(5)]
        );
    }

    public function otpCode()
    {
        return $this->hasOne(otpCode::class, 'user_id');
    }

}
