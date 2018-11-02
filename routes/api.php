<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('notes', 'NoteController@index');
    Route::get('note/{id}', 'NoteController@show');
    Route::post('note', 'NoteController@store');
    Route::put('note', 'NoteController@store');
    Route::delete('note/{id}', 'NoteController@destroy');
});
    
Route::get('/login','ApiController@accessToken');