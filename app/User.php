<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\UserRoleTrait;
use App\Helpers\ReceiverUserTicketTrait;
use App\Notifications\MailResetPasswordNotification;

class User extends Authenticatable
{
    use UserRoleTrait;
    use Notifiable;
    use ReceiverUserTicketTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'username',
        'id_number',
        'factory',
        'position',
        'contact',
        'role',
        'created_by',
        'updated_by',
        'active'
    ];

    protected $appends=[
        'photo',
    ];

    protected $casts=[
        'active'=>'boolean'
    ];



    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getPhotoAttribute()
    {
        return "http://employeephoto.sportscity.com.ph/pams-photo/$this->factory/$this->id_number";
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket','sender_email','email');
    }
    

}
