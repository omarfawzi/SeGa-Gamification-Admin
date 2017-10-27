<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Usersgame
 * 
 * @property int $userID
 * @property int $gameID
 * 
 * @property \App\Models\Game $game
 * @property \App\User $user
 *
 * @package App\Models
 */
class Usersgame extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'userID' => 'int',
		'gameID' => 'int'
	];

	public function game()
	{
		return $this->belongsTo(\App\Models\Game::class, 'gameID');
	}

	public function user()
	{
		return $this->belongsTo(\App\User::class, 'userID');
	}
}
