<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;


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

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['auth:api']], function() {

    // relationship
    Route::post('create-relationship', 'RelationshipController@create');
    Route::post('check-partner-data', 'RelationshipController@checkPartnerData');

    // messages
    Route::post('load-messages', 'MessageController@load');
    Route::post('create-message', 'MessageController@create');
    Route::post('load-unread-messages', 'MessageController@loadUnreadMessages');
    Route::post('mark-messages-as-read', 'MessageController@markMessagesAsRead');
    Route::post('send-image', 'MessageController@sendImage');

    // desktop
    Route::post('get-desktop-background-name', 'DesktopController@getDesktopBackgroundName');
    Route::post('set-desktop-background', 'DesktopController@createDesktopBackground');

    //gallery
    Route::post('add-image-to-gallery', 'GalleryController@addImage');
    Route::post('load-gallery', 'GalleryController@loadGallery');
    Route::post('delete-image-from-gallery', 'GalleryController@deleteImage');
    Route::post('update-description', 'GalleryController@updateDescription');

    // pusher
    Route::post('broadcasting-auth', 'BroadcastingController@broadcastingAuth');

    // user
    Route::post('set-avatar-image', 'AvatarController@create');

});