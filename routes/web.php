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
    return view('fuzja-site.pages.index');
});

Route::get('/contact', function () {
    return view('fuzja-site.pages.contact');
});

Route::get('/polityka-prywatnosci', function() {
    return view('privacyPolicy');
});

Route::get('/regulamin', function() {
    return view('regulations');
});

//Route::get('check-status-of-payments', 'PaymentController@checkStatusOfPayment');