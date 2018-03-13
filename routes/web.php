<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('', function () {
    return redirect('/welcome');
});

Route::get('/welcome','PublicController@index')->name('welcome');
Route::get('/login','Auth\AuthController@index')->name('login');

Route::get('/auth/admin','Auth\AuthController@adminLogin');
Route::get('auth','Auth\AuthController@redirectToProvider');
Route::get('auth/callback','Auth\AuthController@handleProviderCallback');

Route::middleware('auth')->group(function(){
    Route::post('/logout','Auth\AuthController@logout')->name('logout');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/forum/sso', 'ForumController@sso');

    Route::get('discord/auth','DiscordController@redirectToProvider')->name('discordOAuth');
    Route::get('discord/revoke','DiscordController@revokeDiscord');
    Route::get('discord/auth/callback','DiscordController@handleProviderCallback');

});

Route::middleware('auth','\App\Http\Middleware\Admin')->group(function(){
    Route::resource('group', 'GroupController');
    Route::post('/group/addRule','GroupController@addRule');
    Route::post('/group/addMember','GroupController@addMember');
    Route::post('/group/removeMember','GroupController@removeMember');
    Route::post('/group/{$id}/removeMember','GroupController@removeMember');
    Route::get('/api/internal/searchEntities','EntityFinderController@find');
    Route::get('/api/internal/searchUsers','EntityFinderController@findUser');

    Route::get('/mail','MailController@index');
    Route::get('/mail/list','MailController@list');
});
