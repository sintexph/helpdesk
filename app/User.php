<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\UserRoleTrait;
use App\Helpers\ReceiverUserTicketTrait;
use App\Notifications\MailResetPasswordNotification;
use App\Helpers\UserRole;

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

        'shift_start',
        'shift_end',
        'break_time',

        'active'
    ];

    protected $appends=[
        'photo',
        'role_text',
    ];

    protected $casts=[
        'active'=>'boolean',
        'shift_start'=>'integer',
        'shift_end'=>'integer',
        'break_time',
    ];



    public function getRoleTextAttribute()
    {
        return UserRole::role($this->role);
    }

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
