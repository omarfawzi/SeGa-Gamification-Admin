<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Game;
use App\Models\GameNotification;
use App\Models\Gamescharacter;
use App\Models\GamesNotification;
use foo\bar;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Student;
use App\Models\Studentgame;
use App\User;
use App\Models\Usersgame;
use Illuminate\Http\Request;
use Pusher\Pusher;
use stdClass;

class AdminController extends Controller
{
    private $userPhotos;
    private $currentHost;
    private $pusher;

    public function __construct()
    {
        $this->currentHost = 'http://' . request()->getHttpHost() . '/';
        $this->userPhotos = public_path('/assets/admin/images/userPhotos/');
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

    public function index()
    {
        if (auth()->user()->role == 'teacher')
            return redirect()->route('showGames');
        $users = [];
        $usersRole = "";
        if (auth()->user()->role == 'admin') {
            $users = User::where('role', 'company')->get();
            $usersRole = "Companies";
        }
        if (auth()->user()->role == 'company') {
            $users = User::where('role', 'teacher')->where('userID', auth()->user()->id)->get();
            $usersRole = "Teachers";
        }
        return view('adminPanel.usersList', ['users' => $users, 'usersRole' => $usersRole]);
    }

    public function teacherMates()
    {
        $users = User::where('role', 'teacher')->where('userID', auth()->user()->userID)->where('id', '!=', auth()->user()->id)->get();
        return view('adminPanel.usersList', ['users' => $users, 'usersRole' => 'Teachers']);
    }

    public function userProfile()
    {
        $parentID = auth()->user()->userID;
        if (auth()->user()->role == 'company')
            $parentID = auth()->user()->id;
        $company = User::find(auth()->user()->userID);
        $members = User::where('id', '!=', auth()->user()->id)->where('userID', $parentID)->get();
        return view('adminPanel.userProfile', ['company' => $company, 'members' => $members]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($request->username)
            $user->name = $request->username;
        $file = $request->file('imageFile');
        if ($file) {
            $temp = $this->userPhotos . $user->profilePicture;
            if (file_exists($temp) && $user->profilePicture)
                unlink($temp);
            $imageNo = null;
            if (!User::all()->last())
                $imageNo = 1;
            else
                $imageNo = (User::all()->last()->id) + 1;
            $imageName = $imageNo . $file->getClientOriginalName();
            $file->move($this->userPhotos, $imageName);
            $user->profilePicture = $imageName;
        }
        $user->update();
        return back();
    }

    public function pushNews()
    {
        $userGames = Usersgame::where('userID', auth()->user()->id)->with(['game'])->get();
        return view('adminPanel.pushNews', ['userGames' => $userGames, 'user' => auth()->user()]);
    }

    public function sendNews(Request $request)
    {
        $games = $request->games;
        $notification = new GameNotification();
        $notification->notificationContent = $request->message;
        $notification->userID = $request->userID;
        $notification->save();
        foreach ($games as $game) {
            $gameName = Game::find($game)->gameName;
            GamesNotification::insert(['gameID' => $game, 'notificationID' => $notification->notificationID]);
//            $data['content'] = $request->message;
//            $data['newsID'] = $notification->notificationID;
//            $data['image'] = $this->currentHost . 'assets/admin/images/userPhotos/' . $user->profilePicture;
//            $gameChannel = "game" . $game . "_channel";
            $this->pusher->notify(array("news".$game),
                array(
                    'fcm' => array(
                        'notification' => array(
                            'title' => $gameName,
                            'body' => $request->message,
                            'content'=>$request->message
                        ),
                    ),
                ));
//            $this->pusher->trigger($gameChannel, 'news_event', $data);
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function useTeacher(Request $request)
    {
        $userGames = Usersgame::where('userID', $request->teacherID)->with(['game'])->get();
        return view('adminPanel.pushNews', ['userGames' => $userGames, 'user' => User::find($request->teacherID)]);
    }

    public function removeTeacher($teacherID)
    {
        $userGames = Usersgame::where('userID', $teacherID)->get();
        foreach ($userGames as $userGame) {
            $game = Game::where('gameID', $userGame->id)->with(['users'])->first();
            if (count($game->users) == 1) {
                GamesNotification::where('gameID', $userGame->id)->delete();
                $game->delete();
            }
            $userGame->delete();
        }
        GameNotification::where('userID', $teacherID)->delete();
        User::where('id', $teacherID)->delete();
        return back();
    }

    public function editTeacher($teacherID)
    {

        return back();
    }

    public function pushNotifications()
    {
        $this->pusher->notify(array("news"),
            array(
                'fcm' => array(
                    'notification' => array(
                        'title' => 'SeGa Team',
                        'body' => 'Welcome to Sega',
                        'content'=>'hi'
                    ),
                ),
            ));
    }

}
