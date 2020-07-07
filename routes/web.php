<?php

use Illuminate\Support\Facades\Route;


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
    return view('welcome');
});

Route::get('/pusher', function() {
    /*$pusher = new Pusher('4397a9033571317d5522', '3570ecaaf66c6066241e', '962483');
    $auth = $pusher->socket_auth('chat.2', '128403.28678783');

    echo $auth;*/
    //header('HTTP/1.0 403 Forbidden');
    echo("hello");
});

Route::get('/polityka-prywatnosci', function() {
    return view('privacyPolicy');
});

Route::get('check-status-of-payments', 'PaymentController@checkStatusOfPayment');
