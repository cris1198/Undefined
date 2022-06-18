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


Route::post('/login','userController@login');
//Route::get('/logout','userController@logout');

Route::put('/recoverpassword','userController@recoverPassword');

Route::post('/compararCodigo','userController@comparetoKey');

Route::put('/nuevaContraseña','userController@newPassword');







//Route::group(['middleware' => ["auth:sanctum",'docente']], function(){
    //Route::get('/logout','userController@logout');
    Route::post('/reserva', 'ReservaController@store'); //agregar una reserva docente
    Route::get('/reserva/User/{id}', 'ReservaController@getByUserId'); //devuelve una reserva por el usuario quien reserva docente
    Route::get('/reserva/Rechazar/{id}', 'ReservaController@getRejected'); //devuelve las reservas rechazadas de un usuario docente 
    Route::get('/reserva/Aceptar/{id}', 'ReservaController@getAccepted'); //devuelve las reservas aceptadas de un usuario docente
    Route::post('/ambientes/buscar','AmbienteController@search_and_filter'); //buscador de Ambientes por palabra
    Route::post('/reserva', 'ReservaController@store'); //agregar una reserva
    Route::post('/reserva/Recomendacion', 'ReservaController@getRecommendation'); //agregar una reserva
    Route::post('/reserva/periodos/{id}', 'ReservaController@getAvailablePeriods'); //devuelve los ambientes reservados en esa fecha especifica
    Route::get('/grupo/User/{id}', 'GrupoController@getByUser');  //Devuelve materias con grupos segun un usuario
    Route::get('/reserva/Todas/{id}', 'ReservaController@getAcceptAndReject'); //devuelve peticiones rechazadas y aceptadas
//});
Route::post('/ambientes','AmbienteController@store');   //agregar nueva
Route::delete('/reserva/{id}','ReservaController@eliminarReserva');  //eliminar registros
//Route::group(['middleware' => ["auth:sanctum",'adminAgee']], function(){
    //rutas

    Route::get('/reserva/reporteTodos', 'ReservaController@reporteTodos'); //aceptar una reserva   admin
    Route::get('/reserva/reporteAceptados', 'ReservaController@reporteAceptados'); //aceptar una reserva   admin
    Route::get('/reserva/reporteRechazados', 'ReservaController@reporteRechazados'); //aceptar una reserva   admin

    Route::get('/grupo/paraAsignar', 'GrupoController@getToAssign');  //Devuelve materias con grupos para asignar a docentes
    Route::post('/registro','userController@registro'); // crear nuevo docente
    Route::get('/reserva/PorReservar', 'ReservaController@getToReserve'); //devuelve las reservas por aceptar o rechazar
    Route::get('/usuarios','userController@index');  //devuelve todos los usuarios
    Route::get('/reserva','ReservaController@index');  //mostrar todos las reservas
    Route::put('/grupo/Asignar', 'GrupoController@assignUser');
    Route::put('/grupo/AsignarTodos', 'GrupoController@assignAll');

    Route::get('/usuario/{id}','userController@getById'); //devuelve un usuario por su id
    Route::delete('/usuario/{id}','userController@destroy'); //elimina un usuario por su id
    Route::put('/usuario/{id}','userController@update');     //actualiza datos de un usuario
    Route::put('/reserva/Aceptar/{id}', 'ReservaController@acceptReservation'); //aceptar una reserva   admin
    Route::put('/reserva/Rechazar/{id}', 'ReservaController@rejectReservation'); //rechazar una reserva  admin
    Route::get('/reserva/{id}', 'ReservaController@getById'); //devuelve una reserva por su id
    
    Route::put('/ambientes/{id}','AmbienteController@update');  //actualizar registros
    Route::delete('/ambientes/{id}','AmbienteController@destroy');  //eliminar registros
    

//});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





//Route::group(['middleware' => ["auth:sanctum"]], function(){
    Route::get('/logout','userController@logout');
    Route::get('/perfil','userController@perfil');
    Route::get('/ambientes','AmbienteController@index');   //mostrar todos los registros
    Route::get('/ambientes/{id}','AmbienteController@getById'); //devuelve un Ambiente por el id

    Route::get('/grupo/{id}', 'GrupoController@getById');  //Devuelve el grupo segun el id


//});