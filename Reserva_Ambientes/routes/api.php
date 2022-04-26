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

Route::get('/ambientes','App\Http\Controllers\AmbienteController@index');   //mostrar todos los registros
Route::get('/ambientes/buscar','App\Http\Controllers\AmbienteController@search_and_filter'); //buscador de Ambientes por palabra
Route::put('/ambientes/{id}','App\Http\Controllers\AmbienteController@update');  //actualizar registros
Route::delete('/ambientes/{id}','App\Http\Controllers\AmbienteController@destroy');  //eliminar registros
Route::get('/ambientes/{id}','App\Http\Controllers\AmbienteController@getById'); //devuelve un Ambiente por el id

Route::post('/ambientes','App\Http\Controllers\AmbienteController@store');   //agregar nueva