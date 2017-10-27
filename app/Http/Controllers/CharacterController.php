<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Game;
use App\Models\GameNotification;
use App\Models\Gamescharacter;
use App\Models\GamesNotification;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Student;
use App\Models\Studentgame;
use App\User;
use App\Models\Usersgame;
use Pusher\Pusher;
use stdClass;

class CharacterController extends Controller
{
    private $characterPhotos;
    private $pusher;

    public function __construct()
    {
        $this->characterPhotos = public_path('/assets/admin/images/characterPhotos/');
        $options = array(
            'cluster' => 'eu',
            'encrypted' => true
        );
        $this->pusher = new Pusher(
            '67900da86aad75ba2530',
            'f6226999a4931118fbb0',
            '403639',
            $options
        );

    }

    public function addCharacter()
    {
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        return view('adminPanel.add_character', ['userGames' => $userGames]);
    }

    public function addCharacterDB(Request $request)
    {
        $games = $request->games;
        $character = new Character();
        $character->characterName = $request->characterName;
        $character->characterDescription = $request->characterDescription;
        $file = $request->file('imageFile');
        if ($file) {
            $imageNo = null;
            if (!Character::all()->last())
                $imageNo = 1;
            else
                $imageNo = (Character::all()->last()->characterID) + 1;
            $imageName = $imageNo . $file->getClientOriginalName() ;
            $file->move($this->characterPhotos, $imageName);
            $character->characterPhoto = $imageName;
        }
        $character->save();
        if ($games) {
            foreach ($games as $game) {
                Gamescharacter::insert(['gameID' => $game, 'characterID' => $character->characterID]);
            }
        }
        return redirect()->route('characters');
    }

    public function characters()
    {
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        $gamesCharacters = Gamescharacter::where(function ($query) use ($userGames) {
            foreach ($userGames as $key => $userGame) {
                if ($key == 0)
                    $query->where('gameID', $userGame->gameID);
                else
                    $query->orWhere('gameID', $userGame->gameID);
            }
        })->with(['character', 'character.games'])->get();
        return view('adminPanel.characters', ['gamesCharacters' => $gamesCharacters]);
    }

    public function useCharacter(Request $request)
    {
        $characterID = $request->characterID;
        $character = Character::where('characterID', $characterID)->with(['games'])->first();
        return view('adminPanel.use_character', ['character' => $character]);
    }

    public function editCharacter($characterID){
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        $selected = [];
        foreach ($userGames as $userGame){

        }
        $character = Character::find($characterID);
        return view('adminPanel.editCharacter',['character'=>$character,'userGames'=>$userGames]);
    }

    public function editCharacterDB(Request $request){
        $character = Character::find($request->characterID);
        if ($request->characterName)
            $character->characterName = $request->characterName;
        if ($request->characterDescription)
            $character->characterDescription = $request->characterDescription;
        if ($request->file('imageFile')){
            $file = $request->file('imageFile');
            $temp = $this->characterPhotos.$character->characterPhoto;
            if (file_exists($temp) &&$character->characterPhoto)
                unlink($temp);
            $imageNo = null;
            if (!Character::all()->last())
                $imageNo = 1;
            else
                $imageNo = (Character::all()->last()->characterID) + 1;
            $imageName = $imageNo . $file->getClientOriginalName();
            $file->move($this->characterPhotos, $imageName);
            $character->characterPhoto = $imageName;
        }
        $character->update();
        return redirect()->route('characters');
    }


    public function sendMessage(Request $request)
    {
        $games = $request->games;
        $notification = new GameNotification();
        $notification->notificationContent = $request->message;
        $notification->characterID = $request->characterID;
        $notification->save();
        $character = Character::find((int)$request->characterID);
        foreach ($games as $game) {
            GamesNotification::insert(['gameID' => $game, 'notificationID' => $notification->notificationID]);
            $data['content'] = $request->message;
            $data['newsID'] = $notification->notificationID;
            $data['image'] = $this->currentHost . 'assets/admin/images/characterPhotos/' . $character->characterPhoto;
            $gameChannel = "game" . $game . "_channel";
            $this->pusher->trigger($gameChannel, 'news_event', $data);
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteCharacter($characterID){
       $character = Character::where('characterID',$characterID)->with('game_notifications')->first();
       foreach ($character->game_notifications as $game_notification){
           GamesNotification::where('notificationID',$game_notification->notificationID)->delete();
           $game_notification->delete();
       }
        Gamescharacter::where('characterID',$character->characterID)->delete();
       $character->delete();
        return redirect()->route('characters');
    }

}
