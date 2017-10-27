<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameNotification;
use App\Models\GamesNotification;
use App\Models\Student;
use App\Models\Studentgame;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class APIController extends Controller
{
    private $gamesPhotos;
    private $characterPhotos;
    private $saveStudentPhotos;
    private $studentPhotos;
    private $userPhotos;

    public function __construct()
    {
        $currentHost = 'http://' . request()->getHttpHost() . '/';
        $this->gamesPhotos = $currentHost . 'assets/admin/images/gamesPhotos/';
        $this->studentPhotos = $currentHost . 'assets/admin/images/studentPhotos/';
        $this->saveStudentPhotos = public_path('/assets/admin/images/studentPhotos/');
        $this->characterPhotos = $currentHost . 'assets/admin/images/characterPhotos/';
        $this->userPhotos = $currentHost . 'assets/admin/images/userPhotos/';
    }

    public function signUp(Request $request)
    {
        $find = Student::where('email', $request->email)->first();
        if ($find)
            return response()->json(['valid' => 'false'], 200);
        $student = new Student();
        $student->email = $request->email;
        $student->password = $request->password;
        $student->nickName = $request->nickName;
        $student->profilePicture = 'https://www.1plusx.com/app/mu-plugins/all-in-one-seo-pack-pro/images/default-user-image.png';
        $student->save();
        return response()->json(['valid' => 'true', 'userID' => $student->studentID], 200);
    }

    public function signIn(Request $request)
    {
        $student = Student::where('email', $request->email)->first();
        if (!$student)
            return response()->json(['valid' => 'false', 'error' => 'email'], 200);
        if ($request->password != $student->password)
            return response()->json(['valid' => 'false', 'error' => 'password'], 200);
        unset($student->password);
        if ($student->profilePicture[0] != 'h')
            $student->profilePicture = $this->studentPhotos . $student->profilePicture;
        return response()->json(['valid' => 'true', 'userID' => $student->studentID, 'nickName' => $student->nickName, 'student' => $student], 200);
    }

    public function registerGame(Request $request)
    {
        $game = Game::where('gameCode', $request->gameCode)->first();
        if (!$game)
            return response()->json(['valid' => 'false', 'error' => 'not found'], 200);
        $alreadyRegistered = Studentgame::where('gameID', $game->gameID)->where('studentID', $request->userID)->first();
        if ($alreadyRegistered)
            return response()->json(['valid' => 'false', 'error' => 'already registered'], 200);
        Studentgame::insert(['gameID' => $game->gameID, 'studentID' => $request->userID]);
        return response()->json(['valid' => 'true', 'error' => 'not found'], 200);
    }

    public function loadGames(Request $request)
    {
        $studentGames = Studentgame::where('studentID', $request->userID)->with(['game'])->get();
        $games = [];
        $obj = [];
        foreach ($studentGames as $studentGame) {
            $obj['gameID'] = $studentGame->game->gameID;
            $obj['gameName'] = $studentGame->game->gameName;
            $obj['gameDescription'] = $studentGame->game->gameDescription;
            $obj['gamePhoto'] = $this->gamesPhotos . $studentGame->game->gamePhoto;
            $obj['gameCode'] = $studentGame->game->gameCode;
            $games[] = $obj;
        }
        return response()->json($games);
    }

    public function loadProfile(Request $request)
    {
        $student = Student::find($request->userID);
        if ($student->profilePicture[0] != 'h')
            $student->profilePicture = $this->studentPhotos . $student->profilePicture;
        return response()->json($student, 200);
    }

    public function saveProfile(Request $request)
    {
        $student = Student::find($request->userID);
        if ($request->firstName)
            $student->firstName = $request->firstName;
        if ($request->lastName)
            $student->lastName = $request->lastName;
        if ($request->nickName)
            $student->nickName = $request->nickName;
        if ($request->phone)
            $student->phone = $request->phone;
        $student->update();
        if ($request->oldPassword && $request->newPassword) {
            if ($request->oldPassword != $student->password) {
                return response()->json(['error' => 'password'], 200);
            }
            $student->password = $request->newPassword;
        }
        $student->update();
        return response()->json(['status' => 'true'], 200);
    }

    public function saveProfilePicture(Request $request)
    {
        $student = Student::find($request->userID);
        $file = $request->file('profilePicture');
        if ($file) {
            $temp = $this->saveStudentPhotos . $student->profilePicture;
            if (file_exists($temp))
                unlink($temp);
            $imageName = $student->studentID . $file->getClientOriginalName();
            $file->move($this->saveStudentPhotos, $imageName);
            $student->profilePicture = $imageName;
        }
        $student->update();
        return response()->json(['url' => $this->studentPhotos . $student->profilePicture], 200);
    }

    public function loadNews(Request $request)
    {
        $gameNotifications = GamesNotification::where('gameID', $request->gameID)->with(['game_notification', 'game_notification.character', 'game_notification.student', 'game_notification.user'])->get();
        $news = [];
        foreach ($gameNotifications as $gameNotification) {
            $obj = new stdClass();
            $obj->content = $gameNotification->game_notification->notificationContent;
            $obj->newsID = $gameNotification->game_notification->notificationID;
            if ($gameNotification->game_notification->characterID) {
                if ($gameNotification->game_notification->character->characterPhoto)
                    $obj->image = $this->characterPhotos . $gameNotification->game_notification->character->characterPhoto;
                else
                    $obj->image = 'https://www.1plusx.com/app/mu-plugins/all-in-one-seo-pack-pro/images/default-user-image.png';
            } else if ($gameNotification->game_notification->studentID) {
                if ($gameNotification->gameNotification->game_notification->student->profilePicture[0] == 'h')
                    $obj->image = $gameNotification->game_notification->student->profilePicture;
                else
                    $obj->image = $this->studentPhotos . $gameNotification->game_notification->student->profilePicture;
            } else {
                if ($gameNotification->game_notification->user->profilePicture)
                    $obj->image = $this->userPhotos . $gameNotification->game_notification->user->profilePicture;
                else
                    $obj->image = 'https://www.1plusx.com/app/mu-plugins/all-in-one-seo-pack-pro/images/default-user-image.png';
            }
            $news[] = $obj;
        }
        return response()->json(array_reverse($news));
    }

    public function sortPoints($a, $b)
    {
        return $a->points < $b->points;
    }
    public function scoreboard(Request $request)
    {
        $scoreboard = Studentgame::where('gameID', $request->gameID)->with(['student'])->get();
        $students = [];
        foreach ($scoreboard as $item) {
            $object = new stdClass();
            $object->points = $item->points + $item->moodlePoints;
            if ($item->student->profilePicture[0] == 'h')
                $object->studentPicture = $item->student->profilePicture;
            else
                $object->studentPicture = $this->studentPhotos . $item->student->profilePicture;
            $object->progress = $item->progress + $item->moodleProgress;
            if ($item->student->firstName || $item->student->lastName)
                $object->studentName = $item->student->firstName . ' ' . $item->student->lastName;
            else
                $object->studentName = $item->student->nickName;
            $object->studentID = $item->studentID;
            $students[] = $object;
        }

        usort($students,array($this,"sortPoints"));
        return response()->json($students);
    }

    public function newsMessage(Request $request)
    {
        $newsMessage = GameNotification::find($request->newsID);
        return response()->json(['message' => $newsMessage->notificationContent]);
    }
}
