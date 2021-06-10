<?php

use Illuminate\Support\Facades\Route;
use App\Models\UnregisteredUsers;


Route::get('/clear-cache', function(){
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return print_r($_ENV);
});

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

    
    $data = UnregisteredUsers::get();
    
    print_r($data);die;

    return view('welcome');
});
