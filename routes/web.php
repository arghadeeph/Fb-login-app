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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//for fb login
Route::get('/fb-redirect', 'SocialAuthFacebookController@redirect');
Route::get('/callback', 'SocialAuthFacebookController@callback');

Route::post('/processRegistration', 'Auth\RegisterController@registrationProcess')->name('processRegistration');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/myprofile', 'HomeController@myprofile')->name('myprofile');

Route::post('/updateUser', 'HomeController@updateUser');
Route::post('/delete-default-image', 'HomeController@deleteDefaulytImage');
Route::post('/upload-default-image', 'HomeController@uploadDefaulytImage');
Route::post('/delete-image', 'HomeController@deleteImage');




Route::post('/getState', 'HomeController@getState');

Route::post('/getCity', 'HomeController@getCity');





