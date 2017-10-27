<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Gamescharacter
 * 
 * @property int $gameID
 * @property int $characterID
 * 
 * @property \App\Models\Character $character
 * @property \App\Models\Game $game
 *
 * @package App\Models
 */
class Gamescharacter extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gameID' => 'int',
		'characterID' => 'int'
	];

	public function character()
	{
		return $this->belongsTo(\App\Models\Character::class, 'characterID');
	}

	public function game()
	{
		return $this->belongsTo(\App\Models\Game::class, 'gameID');
	}
}
