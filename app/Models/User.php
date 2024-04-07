<?php

namespace App\Models;

use App\Notifications\MyResetPassword;
use App\Notifications\MyVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that appends to returned entities.
     *
     * @var array
     */
    protected $appends = ['photo'];

    /**
     * The getter that return accessible URL for user photo.
     *
     * @var array
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->foto !== null) {
            return url('img/usuarios/default.jpg');
        } else {
            return url('img/usuarios/default.jpg');
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new MyVerifyEmail());
    }

    public function adminlte_image()
    {
        //if ($this->foto !== null) {
            return url('img/usuarios/default.jpg');
        //} else {
            //return url('img/usuarios/default.jpg');
        //}
    }

    public function adminlte_profile_url() {
        return url('perfil');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function getPhotoAttribute()
    {
        if ($this->foto !== null) {
            return url('img/usuarios/default.jpg');
        } else {
            return url('img/usuarios/default.jpg');
        }
    }

}
