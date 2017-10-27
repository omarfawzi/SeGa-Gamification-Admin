<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Oct 2017 22:05:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Character
 * 
 * @property int $characterID
 * @property string $characterName
 * @property string $characterDescription
 * @property string $characterPhoto
 * 
 * @property \Illuminate\Database\Eloquent\Collection $game_notifications
 * @property \Illuminate\Database\Eloquent\Collection $games
 *
 * @package App\Models
 */
class Character extends Eloquent
{
	protected $primaryKey = 'characterID';
	public $timestamps = false;

	protected $fillable = [
		'characterName',
		'characterDescription',
		'characterPhoto'
	];

	public function game_notifications()
	{
		return $this->hasMany(\App\Models\GameNotification::class, 'characterID');
	}

	public function games()
	{
		return $this->belongsToMany(\App\Models\Game::class, 'gamescharacters', 'characterID', 'gameID');
	}
}
