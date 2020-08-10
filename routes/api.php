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
    Route::post('user-is-typing-message', 'MessageController@userIsTypingMessage');
    Route::post('send-sticker', 'MessageController@sendSticker');

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

    // avatar
    Route::post('set-avatar-image', 'AvatarController@create');
    Route::post('get-couples-avatar-names', 'AvatarController@loadCouplesAvatars');

    // security
    Route::post('save-access-code', 'AccessCodeController@saveAccessToken');
    Route::post('remove-access-code', 'AccessCodeController@removeAccessToken');

    // payments
    Route::post('check-status-of-payments', 'PaymentController@checkStatusOfPayment');

    //test
    Route::post('upload-video', 'GalleryController@uploadVideo');

    // dates
    Route::post('save-dates-json', 'DateController@saveDates');
    Route::post('get-dates-json', 'DateController@getDates');  
    
    //version
    Route::post('check-if-new-version-is-available', 'VersionInfoReaderController@getInfo');

});