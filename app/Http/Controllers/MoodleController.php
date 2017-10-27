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
use Symfony\Component\DomCrawler\Crawler;

class MoodleController extends Controller
{
    private $gamesPhotos;
    private $characterPhotos;
    private $pusher;
    private $currentHost;
    private $gameController;

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
        $this->gameController = new GameController();
    }

    public function linkMoodleView()
    {
        return view('adminPanel.linkMoodle');
    }

    public function linkMoodleCheck(Request $request)
    {
        // https://rootseducators.moodle.school
        // admin
        // SeGaTeam_Password1
        $guzzleClient = new GuzzleClient();
        $domain = $request->domain;
        $username = $request->username;
        $password = $request->password;
        $response = $guzzleClient->get($domain . '/login/token.php?username=' . $username . '&password=' . $password . '&service=moodle_mobile_app');
        $data = $response->getBody();
        $data = json_decode($data, true);
        if (!$data) {
            return back()->withErrors(['error' => 'Invalid Domain']);
        }
        foreach ($data as $key => $value) {
            if ($key == 'error') {
                return back()->withErrors(['error' => 'Invalid Credentials']);
            }
        }
        $token = $data['token'];
        session(['moodleToken' => $token]);
        session(['moodleDomain' => $domain]);
        session(['moodleUsername' => $username]);
        session(['moodlePassword' => $password]);
        return redirect()->route('moodleCourses');
    }

    public function moodleCourses()
    {
        $token = session('token');
        $domain = session('domain');
        $moodleCourses = $this->getMoodleData('core_course_get_courses', $token, $domain);
        $courses = [];
        foreach ($moodleCourses as $moodleCourse) {
            $game = Game::where('moodleID', $moodleCourse['id'])->first();
            $object = new stdClass();
            $object->courseID = $moodleCourse['id'];
            $object->courseName = $moodleCourse['fullname'];
            $object->function = 'Import';
            if ($game) {
                $object->function = 'Update';
            }
            $courses[] = $object;
        }
        return view('adminPanel.moodleCourses', ['courses' => $courses]);
    }

    public function getMoodleData($wsfunction, $token, $domain, $courseID = -1)
    {
        $guzzleClient = new GuzzleClient();
        $request = $domain . '/webservice/rest/server.php?wstoken=' . $token . '&wsfunction=' . $wsfunction . '&moodlewsrestformat=json';
        $response = $guzzleClient->get($request);
        $data = $response->getBody();
        $data = json_decode($data, true);
        return $data;
    }

    public function updateCourse(Request $request)
    {
        $token = session('moodleToken');
        $domain = session('moodleDomain');
        $wsfunction = 'core_enrol_get_enrolled_users';
        $guzzleClient = new GuzzleClient();
        $requestURL = $domain . '/webservice/rest/server.php?wstoken=' . $token . '&wsfunction=' . $wsfunction . '&moodlewsrestformat=json&courseid=' . $request->courseID;
        $response = $guzzleClient->get($requestURL);
        $data = $response->getBody();
        $data = json_decode($data, true);
        foreach ($data as $key => $datum) {
            $flag = false;
            for ($i = 0; $i < count($datum['roles']); $i++) {
                if ($datum['roles'][$i]['shortname'] == 'student') {
                    $flag = true;
                    break;
                }
            }
            if (!$flag)
                unset($data[$key]);
        }
        $exists = Game::where('moodleID', $request->courseID)->first();
        if ($exists) {
            $exists->gameName = $request->courseName;
            $exists->update();
            $this->gameController->addStudentToGameDB($data, $exists->gameID);
        } else {
            $game = new Game();
            $game->moodleID = $request->courseID;
            $game->gameName = $request->courseName;
            $game->save();
            $users = User::where('userID', auth()->user()->userID)->get();
            foreach ($users as $user) {
                Usersgame::insert(['gameID' => $game->gameID, 'userID' => $user->id]);
            }
            $this->gameController->addStudentToGameDB($data, $game->gameID);
        }
        return $this->scrapPoints($request->courseID);
    }

    public function unlinkMoodle(Request $request)
    {
        $request->session()->forget('moodleDomain');
        $request->session()->forget('moodleToken');
        $request->session()->forget('moodleUsername');
        $request->session()->forget('moodlePassword');
        return back();
    }

    public function scrapPoints($courseID)
    {
        $game = Game::where('moodleID', $courseID)->first();
        $username = session('moodleUsername');
        $domain = session('moodleDomain');
        $password = session('moodlePassword');
        $goutteClient = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $crawler = $goutteClient->request('GET', $domain . '/login/index.php');
        $form = $crawler->selectButton('Log in')->form();
        $crawler = $goutteClient->submit($form, array('username' => $username, 'password' => $password));
        $crawler = $goutteClient->request('GET', $domain . '/blocks/xp/index.php/ladder/' . $courseID);
        $pages = $crawler->filter('div > .paging')->text();
        $pagesArray = explode(' ', $pages);
        $mx = -1;
        foreach ($pagesArray as $page) {
            if (intval($page) > $mx)
                $mx = intval($page);
        }
        $users = [];
        $xps = [];
        $progress = [];
        for ($i = 0; $i < $mx; $i++) {
            $crawler = $goutteClient->request('GET', $domain . '/blocks/xp/index.php/ladder/' . $courseID . '?page=' . $i);
            $table = $crawler->filter('tbody');
            foreach ($table->filter('tr') as $key => $domElement) {
                $domElement = new Crawler($domElement);
                if ($domElement->filter('a')->count() > 0) {
                    $query_str = parse_url($domElement->filter('a')->attr('href'), PHP_URL_QUERY);
                    parse_str($query_str, $query_params);
                    $users[] = $query_params['id'];
                    $xps[] = $domElement->filter('.pts')->text();
                    $temp = $domElement->filter('.xp-bar')->attr('style');
                    $pr = "";
                    for ($i = 0 ; $i < strlen($temp) ; $i++){
                        if (is_numeric($temp[$i])){
                            $pr .= $temp[$i];
                        }
                    }
                    $progress[] = $pr;
                }
            }
        }
        foreach ($users as $key => $user) {
            $student = Student::where('moodleID', $user)->first();
            if ($student) {
                Studentgame::where(['gameID' => $game->gameID, 'studentID' => $student->studentID])
                    ->update(['moodlePoints' => intval(str_replace(',', '', $xps[$key])),'moodleProgress'=>intval($progress[$key])]);
            }
        }
        return redirect()->route('showGames');
    }
}
