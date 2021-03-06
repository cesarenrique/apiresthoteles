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

Route::resource('users','User\UserController');

Route::resource('hotels','Hotel\HotelController');

Route::get('hotels/{hotel}/habitacions','Habitacion\HabitacionController@index');
Route::post('hotels/{hotel}/habitacions','Habitacion\HabitacionController@store');
Route::post('hotels/{hotel}/habitacions/create','Habitacion\HabitacionController@create');
Route::get('hotels/{hotel}/habitacions/{habitacion}','Habitacion\HabitacionController@show');
Route::put('hotels/{hotel}/habitacions/{habitacion}','Habitacion\HabitacionController@update');
Route::delete('hotels/{hotel}/habitacions/{habitacion}','Habitacion\HabitacionController@destroy');
Route::get('hotels/{hotel}/habitacions/{habitacion}/edit','Habitacion\HabitacionController@edit');
Route::get('hotels/{hotel}/habitacions/{habitacion}/libre','Habitacion\HabitacionController@libre');
Route::get('hotels/{hotel}/habitacions/{habitacion}/ocupada','Habitacion\HabitacionController@ocupada');

Route::resource('clientes','Cliente\ClienteController');
Route::get('clientes/{cliente}/tarjetas','Tarjeta\TarjetaController@index');
Route::post('clientes/{cliente}/tarjetas','Tarjeta\TarjetaController@store');
Route::post('clientes/{cliente}/tarjetas/create','Tarjeta\TarjetaController@screate');
Route::get('clientes/{cliente}/tarjetas/{tarjeta}','Tarjeta\TarjetaController@show');
Route::put('clientes/{cliente}/tarjetas/{tarjeta}','Tarjeta\TarjetaController@update');
Route::delete('clientes/{cliente}/tarjetas/{tarjeta}','Tarjeta\TarjetaController@destroy');
Route::get('clientes/{cliente}/tarjetas/{tarjeta}/edit','Tarjeta\TarjetaController@edit');

Route::get('hotels/{hotel}/habitacions/{habitacion}/reservar/{cliente}','Habitacion\HabitacionController@reservar');
