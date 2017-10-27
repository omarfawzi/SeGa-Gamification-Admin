<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Game
 * 
 * @property int $gameID
 * @property string $gameName
 * @property string $gameDescription
 * @property string $gamePhoto
 * @property string $gameCode
 * @property \Carbon\Carbon $created_at
 * @property int $moodleID
 * 
 * @property \Illuminate\Database\Eloquent\Collection $games_notifications
 * @property \Illuminate\Database\Eloquent\Collection $characters
 * @property \Illuminate\Database\Eloquent\Collection $students
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Game extends Eloquent
{
	protected $table = 'game';
	protected $primaryKey = 'gameID';
	public $timestamps = false;

	protected $casts = [
		'moodleID' => 'int'
	];

	protected $fillable = [
		'gameName',
		'gameDescription',
		'gamePhoto',
		'gameCode',
		'moodleID'
	];

	public function games_notifications()
	{
		return $this->hasMany(\App\Models\GamesNotification::class, 'gameID');
	}

	public function characters()
	{
		return $this->belongsToMany(\App\Models\Character::class, 'gamescharacters', 'gameID', 'characterID');
	}

	public function students()
	{
		return $this->belongsToMany(\App\Models\Student::class, 'studentgames', 'gameID', 'studentID')
					->withPivot('progress', 'points', 'moodlePoints', 'moodleProgress');
	}

	public function users()
	{
		return $this->belongsToMany(\App\User::class, 'usersgames', 'gameID', 'userID');
	}
}
