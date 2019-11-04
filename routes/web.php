<?php

use App\Events\WebsocketDemoEvent;
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
/* 
    $message = "Se ejecutó una accion";
    $from =1;
    $to = 2;

    broadcast(new WebsocketDemoEvent($from,$message,$to)); */

    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//mis rutas
Route::get('/messages/{from}/{to}', 'MessageController@getMessages');//para obtener mensajes 
Route::post('/messages', 'MessageController@store');//para almacenar mensajes
Route::get('/get_connected', 'UserController@getConnected');

