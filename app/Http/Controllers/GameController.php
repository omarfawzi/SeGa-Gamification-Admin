<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Pusher\Pusher;
use stdClass;

class GameController extends Controller
{
    private $gamesPhotos;
    private $characterPhotos;
    private $pusher;
    private $currentHost;

    public function __construct()
    {
        $this->currentHost = 'http://' . request()->getHttpHost() . '/';
        $this->gamesPhotos = public_path('/assets/admin/images/gamesPhotos/');
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

    public function showGames()
    {
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        return view('adminPanel.games', ['userGames' => $userGames]);
    }

    public function addGames()
    {
        $teachers = User::where('role', 'teacher')->where('userID', auth()->user()->userID)->where('id', '!=', auth()->user()->id)->get();
        return view('adminPanel.add_games', ['teachers' => $teachers]);
    }

    public function addGameToDB(Request $request)
    {
        $this->validate($request, [
            'gameCode' => 'unique:game'
        ]);
        $teachers = $request->teachers;
        $game = new Game();
        $game->gameCode = $request->gameCode;
        $game->gameDescription = $request->gameDescription;
        $game->gameName = $request->gameName;
        $file = $request->file('imageFile');
        $imageNo = null;
        if (!Game::all()->last())
            $imageNo = 1;
        else
            $imageNo = (Game::all()->last()->gameID) + 1;
        $imageName = $imageNo . '.' . $file->getClientOriginalName();
        $file->move($this->gamesPhotos, $imageName);
        $game->gamePhoto = $imageName;
        $game->save();
        Usersgame::insert(['gameID' => $game->gameID, 'userID' => auth()->user()->id]);
        foreach ($teachers as $teacher) {
            Usersgame::insert(['gameID' => $game->gameID, 'userID' => $teacher]);
        }
        return redirect()->route('showGames');
    }

    public function editGame(Request $request)
    {
        $selected = [];
        $teachers = User::where('role', 'teacher')->where('userID', auth()->user()->userID)->where('id', '!=', auth()->user()->id)->get();
        foreach ($teachers as $teacher) {
            $selected[$teacher->id] = 0;
        }
        $game = Game::where('gameID', $request->gameID)->with(['users' => function ($query) {
            $query->where('id', '!=', auth()->user()->id);
        }])->first();
        foreach ($game->users as $user) {
            $selected[$user->id] = 1;
        }
        return view('adminPanel.edit_game', ['game' => $game, 'teachers' => $teachers, 'selected' => $selected]);
    }

    public function editGameDB(Request $request)
    {
        $teachers = $request->teachers;
        $game = Game::find($request->gameID);
        $game->gameCode = $request->gameCode;
        $game->gameDescription = $request->gameDescription;
        $game->gameName = $request->gameName;
        $file = $request->file('imageFile');
        if ($file) {
            $temp = $this->gamesPhotos . $game->gamePhoto;
            if (file_exists($temp) && $game->gamePhoto)
                unlink($temp);
            $imageNo = null;
            if (!Game::all()->last())
                $imageNo = 1;
            else
                $imageNo = (Game::all()->last()->gameID) + 1;
            $imageName = $imageNo.$file->getClientOriginalName() ;
            $file->move($this->gamesPhotos, $imageName);
            $game->gamePhoto = $imageName;
        }
        $game->update();
        Usersgame::where('gameID', $game->gameID)->delete();
        Usersgame::insert(['gameID' => $game->gameID, 'userID' => auth()->user()->id]);
        if ($teachers) {
            foreach ($teachers as $teacher) {
                Usersgame::insert(['gameID' => $game->gameID, 'userID' => $teacher]);
            }
        }
        return redirect()->route('showGames');
    }

    public function deleteGame($gameID)
    {
        $game = Game::find($gameID);
        $temp = $this->gamesPhotos . $game->gamePhoto;
        if (file_exists($temp) && $game->gamePhoto)
            unlink($temp);
        Usersgame::where('gameID', $game->gameID)->delete();
        Studentgame::where('gameID', $game->gameID)->delete();
        $gamesNotification = GamesNotification::where('gameID', $game->gameID)->get();
        foreach ($gamesNotification as $item){
            GameNotification::where('notificationID',$item->notificationID)->delete();
            $item->delete();
        }
        $gamesCharacters = Gamescharacter::where('gameID', $game->gameID)->get();
        foreach ($gamesCharacters as $gamesCharacter) {
            Character::where('characterID', $gamesCharacter->characterID)->delete();
            $gamesCharacter->delete();
        }
        $game->delete();
        return redirect()->route('showGames');
    }

    public function addStudentToGame()
    {
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        return view('adminPanel.addStudentToGame', ['userGames' => $userGames]);
    }

    public function addStudentToGameDB($data,$gameID)
    {
        $token = session('moodleToken');
        if (!$token){
            return redirect()->route('linkMoodle');
        }
        foreach ($data as $datum) {
            $exists = Student::where('moodleID',$datum['id'])->first();
            if ($exists){
//                $exists->nickName = $datum['username'];
////                $exists->email = $datum['email'];
////                $exists->password = $datum['username'];
                $exists->profilePicture = $datum['profileimageurl'];
                if (!strpos($exists->profilePicture, 'secure')) {
                    $part1 = substr($exists->profilePicture, 0, 37);
                    $part2 = 'webservice/' . substr($exists->profilePicture, 37, strlen($exists->profilePicture)) .'&token='. $token;
                    $exists->profilePicture = $part1 . $part2;
                }
                $exists->moodleID = $datum['id'];
                $exists->firstName = $datum['firstname'];
                $exists->lastName = $datum['lastname'];
                $exists->update();
                $existsInGame =  Studentgame::where('gameID',$gameID)->where('studentID',$exists->studentID)->first();
                if (!$existsInGame)
                    Studentgame::insert(['gameID' => $gameID, 'studentID' => $exists->studentID]);
            }
            else {
                $student = new Student();
                $student->nickName = $datum['username'];
                $student->email = $datum['email'];
                $student->password = $datum['username'];
                $student->profilePicture = $datum['profileimageurl'];
                if (!strpos($student->profilePicture, 'secure')) {
                    $part1 = substr($student->profilePicture, 0, 37);
                    $part2 = 'webservice/' . substr($student->profilePicture, 37, strlen($student->profilePicture)) .'&token='. $token;
                    $student->profilePicture = $part1 . $part2;
                }
                $student->moodleID = $datum['id'];
                $student->firstName = $datum['firstname'];
                $student->lastName = $datum['lastname'];
                $student->save();
                Studentgame::insert(['gameID' => $gameID, 'studentID' => $student->studentID]);
            }
        }
        return redirect()->route('showGames');
    }

    public function externalScoreBoard($gameID)
    {
        $scoreboard = Studentgame::where('gameID', $gameID)->orderBy('points', 'desc')->with(['student', 'game'])->get();
        return view('adminPanel.externalScoreBoard', ['scoreboard' => $scoreboard, 'gameID' => $gameID]);
    }

    public function sortPoints($a, $b)
    {
        return ($a->points+$a->moodlePoints) < ($b->points+$b->moodlePoints);
    }

    public function scoreBoard(Request $request)
    {
        $getStudents = Studentgame::where('gameID', $request->gameID)->with(['student', 'game'])->get();
        $scoreboard = [];
        foreach ($getStudents as $student){
            $scoreboard[] = $student;
        }
        usort($scoreboard,array($this,'sortPoints'));
        return view('adminPanel.scoreBoard', ['scoreboard' => $scoreboard]);
    }

    public function studentPoints(Request $request)
    {
        $gameID = $request->gameID;
        $progress = $request->progress;
        $points = $request->points;
        $studentID = $request->studentID;
        $data['points'] = $points;
        $data['progress'] = $progress;
        $data['studentID'] = $studentID;
        $this->pusher->trigger('game' . $gameID . '_channel', 'scoreboard_event', $data);
        Studentgame::where(['studentID' => $studentID, 'gameID' => $gameID])->update(['points' => $points, 'progress' => $progress]);
    }

}
