<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Studentgame
 * 
 * @property int $studentID
 * @property int $gameID
 * @property int $progress
 * @property int $points
 * @property int $moodlePoints
 * @property int $moodleProgress
 * 
 * @property \App\Models\Game $game
 * @property \App\Models\Student $student
 *
 * @package App\Models
 */
class Studentgame extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'studentID' => 'int',
		'gameID' => 'int',
		'progress' => 'int',
		'points' => 'int',
		'moodlePoints' => 'int',
		'moodleProgress' => 'int'
	];

	protected $fillable = [
		'progress',
		'points',
		'moodlePoints',
		'moodleProgress'
	];

	public function game()
	{
		return $this->belongsTo(\App\Models\Game::class, 'gameID');
	}

	public function student()
	{
		return $this->belongsTo(\App\Models\Student::class, 'studentID');
	}
}
