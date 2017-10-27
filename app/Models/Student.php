<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Student
 * 
 * @property int $studentID
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string $nickName
 * @property string $phone
 * @property string $profilePicture
 * @property int $moodleID
 * 
 * @property \Illuminate\Database\Eloquent\Collection $game_notifications
 * @property \Illuminate\Database\Eloquent\Collection $games
 *
 * @package App\Models
 */
class Student extends Eloquent
{
	protected $table = 'student';
	protected $primaryKey = 'studentID';
	public $timestamps = false;

	protected $casts = [
		'moodleID' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'firstName',
		'lastName',
		'email',
		'password',
		'nickName',
		'phone',
		'profilePicture',
		'moodleID'
	];

	public function game_notifications()
	{
		return $this->hasMany(\App\Models\GameNotification::class, 'studentID');
	}

	public function games()
	{
		return $this->belongsToMany(\App\Models\Game::class, 'studentgames', 'studentID', 'gameID')
					->withPivot('progress', 'points', 'moodlePoints', 'moodleProgress');
	}
}
