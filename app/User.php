<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'userID' => 'int'
    ];

    protected $fillable = [
        'name', 'email', 'password','role','userID','profilePicture','educationRole'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'userID');
    }

    public function users()
    {
        return $this->hasMany(\App\User::class, 'userID');
    }

    public function games()
    {
        return $this->belongsToMany(\App\Models\Game::class, 'usersgames', 'userID', 'gameID');
    }

    public function game_notifications()
    {
        return $this->hasMany(\App\Models\GameNotification::class, 'userID');
    }

}
