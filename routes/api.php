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


Route::post('/login', 'AuthController@auth');
Route::group(['middleware' => 'token'], function($router) {
    Route::get('/teste', 'TesteController@index');

    Route::group(['prefix' => 'vacation_plan'], function($router){
        $router->get('/', 'VacationPlanController@list');
        $router->get('/{id}', 'VacationPlanController@show');
        $router->post('/', 'VacationPlanController@create');
        $router->put('/{id}', 'VacationPlanController@update');
        $router->delete('/{id}', 'VacationPlanController@delete');
        
        $router->post('/export', 'PdfController@export');
    });
});