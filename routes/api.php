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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('medicalspecialties', 'MedicalSpecialtiesController@GetallSpcecialties')->middleware('apilang');
Route::get('getSpecializations/{specializations_id}/{type}/{user_id}', 'MedicalSpecialtiesController@GetSpecializations')->middleware('apilang');

// users
Route::post('register/{lang}', 'UsersController@Register');
Route::post('login/{lang}', 'UsersController@Login');
// users end

/*fav*/

Route::get('myfavlist/{id}', 'FavouriteController@GetMyfavourit')->middleware('apilang');
Route::post('addtofavourit/{lang}', 'FavouriteController@AddToFavourit');
Route::delete('deletefrommylist/{id}', 'FavouriteController@DeleteFromFavourit');
/*fav*/

/* docotors Resrvations*/

Route::get('getmyresevationslist/{id}', 'ReservationsController@GetMyReservationsList')->middleware('apilang');
Route::get('avaliabletime/{id}', 'ReservationsController@GetDoctorsCalndars')->middleware('apilang');
Route::post('createresvations', 'ReservationsController@CreateReservations');
Route::delete('cancelreservations/{id}', 'ReservationsController@CancelReservations');

/*end*/

/*city*/
Route::get('getcity', 'CityController@GetCity')->middleware('apilang');
/*end*/
/*zone*/
Route::get('getzone/{id}', 'ZoneController@getZone')->middleware('apilang');
/*end*/


