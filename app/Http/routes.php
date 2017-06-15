<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/api/wx_login', [
    "uses" => "UserController@loginAction"
]);

Route::match(['get', 'post'], '/api/prediction', [
    "uses" => "PredictionController@predict"
]);

Route::match(['get', 'post'], '/api/prediction/submit', [
    "uses" => "PredictionController@insertPrediction"
]);

Route::match(['get', 'post'], '/api/map', [
    "uses" => "MapController@getMap"
]);

Route::match(['get', 'post'], '/api/submit_testcase', [
    "uses" => "TestcaseController@submitTestcase"
]);

Route::match(['get', 'post'], '/api/weight', [
    "uses" => "PredictionController@addWeight"
]);

Route::get('/api/prediction/generate', [
    "uses" => "PredictionController@generatePrediction"
]);
Route::get('/initmap', [
    "uses" => "MapController@initMap"
]);