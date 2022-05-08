<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ambientes','AmbienteController@index');   //mostrar todos los registros
Route::post('/ambientes/buscar','AmbienteController@search_and_filter'); //buscador de Ambientes por palabra
Route::put('/ambientes/{id}','AmbienteController@update');  //actualizar registros
Route::delete('/ambientes/{id}','AmbienteController@destroy');  //eliminar registros
Route::get('/ambientes/{id}','AmbienteController@getById'); //devuelve un Ambiente por el id
Route::post('/ambientes','AmbienteController@store');   //agregar nueva

Route::get('/reserva','ReservaController@index');  //mostrar todos las reservas
Route::post('/reserva', 'ReservaController@store'); //agregar una reserva
Route::put('/reserva/Aceptar/{id}', 'ReservaController@acceptReservation'); //aceptar una reserva
Route::put('/reserva/Rechazar/{id}', 'ReservaController@rejectReservation'); //rechazar una reserva
Route::get('/reserva/{id}', 'ReservaController@getById'); //devuelve una reserva por su id
Route::get('/reserva/User/{id}', 'ReservaController@etByUserId'); //devuelve una reserva por el usuario quien reserva
Route::get('/reserva/Aceptar/{id}', 'ReservaController@getAccepted'); //devuelve las reservas aceptadas de un usuario
Route::get('/reserva/Rechazar/{id}', 'ReservaController@getRejected'); //devuelve las reservas rechazadas de un usuario

