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


Route::get('/articulos','App\Http\Controllers\AmbienteController@getById'); //devuelve un Ambiente por el id
Route::get('/articulos/buscar','App\Http\Controllers\AmbienteController@search'); //buscador de Ambientes por palabra
Route::get('/articulos/filtro','App\Http\Controllers\AmbienteController@filter'); //filtro de Ambientes
Route::get('/articulos/filtroCapacidad','App\Http\Controllers\AmbienteController@rangeFilter'); //filtro de Ambientes por un rango de capacidad