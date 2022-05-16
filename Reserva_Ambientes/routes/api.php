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
Route::post('registro','userController@registro');
Route::post('login','userController@login');



Route::group(['middleware' => ["auth:sanctum",'docente']], function(){
    Route::get('logout','userController@logout');
    Route::post('/reserva', 'ReservaController@store'); //agregar una reserva docente
    Route::get('/reserva/User/{id}', 'ReservaController@getByUserId'); //devuelve una reserva por el usuario quien reserva docente
    Route::get('/reserva/Rechazar/{id}', 'ReservaController@getRejected'); //devuelve las reservas rechazadas de un usuario docente 
    Route::get('/reserva/Aceptar/{id}', 'ReservaController@getAccepted'); //devuelve las reservas aceptadas de un usuario docente
});


Route::group(['middleware' => ["auth:sanctum",'adminAgee']], function(){
    //rutas
    Route::get('logout','userController@logout');
    Route::get('perfil','userController@perfil');
    Route::put('/reserva/Aceptar/{id}', 'ReservaController@acceptReservation'); //aceptar una reserva   admin
    Route::put('/reserva/Rechazar/{id}', 'ReservaController@rejectReservation'); //rechazar una reserva  admin
    Route::get('/reserva/{id}', 'ReservaController@getById'); //devuelve una reserva por su id
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ambientes','AmbienteController@index')->middleware('adminAgee');   //mostrar todos los registros
Route::post('/ambientes/buscar','AmbienteController@search_and_filter'); //buscador de Ambientes por palabra
Route::put('/ambientes/{id}','AmbienteController@update');  //actualizar registros
Route::delete('/ambientes/{id}','AmbienteController@destroy');  //eliminar registros
Route::get('/ambientes/{id}','AmbienteController@getById'); //devuelve un Ambiente por el id
Route::post('/ambientes','AmbienteController@store');   //agregar nueva

Route::get('/reserva','ReservaController@index');  //mostrar todos las reservas
Route::post('/reserva', 'ReservaController@store'); //agregar una reserva
Route::get('/reserva/PorReservar', 'ReservaController@getToReserve'); //devuelve las reservas por aceptar o rechazar
Route::put('/reserva/Aceptar/{id}', 'ReservaController@acceptReservation'); //aceptar una reserva
Route::put('/reserva/Rechazar/{id}', 'ReservaController@rejectReservation'); //rechazar una reserva
Route::get('/reserva/{id}', 'ReservaController@getById'); //devuelve una reserva por su id
Route::get('/reserva/User/{id}', 'ReservaController@getByUserId'); //devuelve una reserva por el usuario quien reserva
Route::get('/reserva/Aceptar/{id}', 'ReservaController@getAccepted'); //devuelve las reservas aceptadas de un usuario
Route::get('/reserva/Rechazar/{id}', 'ReservaController@getRejected'); //devuelve las reservas rechazadas de un usuario
Route::get('/reserva/{id}/periodos', 'ReservaController@getAvailablePeriods'); //devuelve los ambientes reservados en esa fecha especifica








Route::get('/reserva/Todas/{id}', 'ReservaController@getAcceptAndReject'); //rechazar una reserva