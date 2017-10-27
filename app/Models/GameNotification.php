<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GameNotification
 * 
 * @property int $notificationID
 * @property string $notificationContent
 * @property int $studentID
 * @property int $characterID
 * @property int $userID
 * 
 * @property \App\Models\Character $character
 * @property \App\Models\Student $student
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $games_notifications
 *
 * @package App\Models
 */
class GameNotification extends Eloquent
{
	protected $primaryKey = 'notificationID';
	public $timestamps = false;

	protected $casts = [
		'studentID' => 'int',
		'characterID' => 'int',
		'userID' => 'int'
	];

	protected $fillable = [
		'notificationContent',
		'studentID',
		'characterID',
		'userID'
	];

	public function character()
	{
		return $this->belongsTo(\App\Models\Character::class, 'characterID');
	}

	public function student()
	{
		return $this->belongsTo(\App\Models\Student::class, 'studentID');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'userID');
	}

	public function games_notifications()
	{
		return $this->hasMany(\App\Models\GamesNotification::class, 'notificationID');
	}
}
