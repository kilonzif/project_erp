<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravelista\Comments\Commenter;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Commenter;
//    use \OwenIt\Auditing\Auditable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','ace','institution','phone'
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

    public function ace_()
    {
        return $this->belongsTo('App\Ace','ace');
    }

    public function institution_()
    {
        return $this->belongsTo('App\Institution','institution');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }
    public function acecomment(){
        $this->hasMany('App\AceComment');
    }
}
