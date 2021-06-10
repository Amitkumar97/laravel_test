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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([ "namespace" => "Apis"], function(){
    Route::post('/send-invitation', 'UserApisController@sendInvitationLink')->name('sendInvitationLink');
    Route::post('/signup/{id}', 'UserApisController@signupUsingInvitation')->name('signupUsingInvitation');
    Route::post('/match-otp', 'UserApisController@matchOTP')->name('matchOTP');
    Route::post('/login', 'UserApisController@login')->name('login');
    Route::post('/update-profile', 'UserApisController@updateProfile')->name('updateProfile');
});
