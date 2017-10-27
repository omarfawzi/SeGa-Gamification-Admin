<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GamesNotification
 * 
 * @property int $gameID
 * @property int $notificationID
 * 
 * @property \App\Models\Game $game
 * @property \App\Models\GameNotification $game_notification
 *
 * @package App\Models
 */
class GamesNotification extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gameID' => 'int',
		'notificationID' => 'int'
	];

	public function game()
	{
		return $this->belongsTo(\App\Models\Game::class, 'gameID');
	}

	public function game_notification()
	{
		return $this->belongsTo(\App\Models\GameNotification::class, 'notificationID');
	}
}
