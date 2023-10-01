<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($user)            // Регистрируем обработчик события created(), который
        {                                                   // будет вызываться каждый раз при создании нового
            $userAccsess = new UserAccess();                // экземпляра модели UserAccess (просто новой строки
                                                            // в таблице users).
            $userAccsess->user_id = $user->id;
            $userAccsess->save();
        });
    }

    public function access()
    {
        return $this->hasMany(UserAccess::class);
    }
}
