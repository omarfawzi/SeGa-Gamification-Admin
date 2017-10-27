<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('adminHome');
});

Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
    Route::group(['middleware' => 'auth'], function () {

        // Global routes
        Route::get('/', 'AdminController@index')->name('adminHome');
        Route::get('userProfile', 'AdminController@userProfile')->name('userProfile');
        Route::post('updateProfile', 'AdminController@updateProfile')->name('updateProfile');
        Route::get('sendNews', 'AdminController@sendNews')->name('sendNews');
        // Teacher routes
        Route::group(['middleware' => 'teacher'], function () {
            // Admin Controller
            Route::get('teacherMates', 'AdminController@teacherMates')->name('teacherMates');
            Route::get('pushNews', 'AdminController@pushNews')->name('pushNews');
            Route::get('pushNotifications','AdminController@pushNotifications')->name('pushNotifications');
            // Game Controller
            Route::get('showGames', 'GameController@showGames')->name('showGames');
            Route::get('addGames', 'GameController@addGames')->name('addGames');
            Route::post('addGameToDB', 'GameController@addGameToDB')->name('addGameToDB');
            Route::get('editGame', 'GameController@editGame')->name('editGame');
            Route::post('editGameDB', 'GameController@editGameDB')->name('editGameDB');
            Route::get('deleteGame/{gameID}', 'GameController@deleteGame')->name('deleteGame');
            Route::get('scoreBoard', 'GameController@scoreBoard')->name('scoreBoard');
            Route::get('studentPoints', 'GameController@studentPoints')->name('studentPoints');
            Route::get('externalScoreBoard/{gameID}', 'GameController@externalScoreBoard')->name('externalScoreBoard');
            Route::get('addStudentToGame', 'GameController@addStudentToGame')->name('addStudentToGame');
            Route::post('addStudentToGameDB', 'GameController@addStudentToGameDB')->name('addStudentToGameDB');

            // Character Controller
            Route::get('addCharacter', 'CharacterController@addCharacter')->name('addCharacter');
            Route::post('addCharacterDB', 'CharacterController@addCharacterDB')->name('addCharacterDB');
            Route::get('characters', 'CharacterController@characters')->name('characters');
            Route::get('useCharacter', 'CharacterController@useCharacter')->name('useCharacter');
            Route::get('sendMessage', 'CharacterController@sendMessage')->name('sendMessage');
            Route::get('editCharacter/{characterID}', 'CharacterController@editCharacter')->name('editCharacter');
            Route::post('editCharacterDB', 'CharacterController@editCharacterDB')->name('editCharacterDB');
            Route::get('deleteCharacter/{characterID}', 'CharacterController@deleteCharacter')->name('deleteCharacter');

            // Moodle Controller
            Route::group(['middleware' => 'moodleAuth'], function () {
                Route::get('linkMoodle', 'MoodleController@linkMoodleView')->name('linkMoodle');
                Route::get('linkMoodleCheck', 'MoodleController@linkMoodleCheck')->name('linkMoodleCheck');
            });

            Route::group(['middleware' => 'moodle'], function () {
                Route::get('moodleCourses', 'MoodleController@moodleCourses')->name('moodleCourses');
                Route::get('updateCourse', 'MoodleController@updateCourse')->name('updateCourse');
                Route::get('unlinkMoodle', 'MoodleController@unlinkMoodle')->name('unlinkMoodle');
                Route::get('scrapPoints', 'MoodleController@scrapPoints')->name('scrapPoints');
            });

        });

        // Company Routes
        Route::group(['middleware' => 'company'], function () {
            Route::get('useTeacher','AdminController@useTeacher')->name('useTeacher');
            Route::get('removeTeacher/{teacherID}','AdminController@removeTeacher')->name('removeTeacher');
            Route::get('editTeacher/{teacherID}','AdminController@editTeacher')->name('editTeacher');
        });

        // Admin Routes
        Route::group(['middleware' => 'admin'], function () {
        });

    });
});

// Api Controller
Route::group(['prefix' => 'api'], function () {
    Route::post('signUp', 'APIController@signUp');
    Route::post('signIn', 'APIController@signIn');
    Route::post('registerGame', 'APIController@registerGame');
    Route::post('loadGames', 'APIController@loadGames');
    Route::post('loadProfile', 'APIController@loadProfile');
    Route::post('saveProfile', 'APIController@saveProfile');
    Route::post('saveProfilePicture', 'APIController@saveProfilePicture');
    Route::post('loadNews', 'APIController@loadNews');
    Route::post('scoreboard', 'APIController@scoreboard');
    Route::post('newsMessage', 'APIController@newsMessage');
});

Route::any('{catchall}', function ($page) {
    return redirect('/');
})->where('catchall', '(.*)');