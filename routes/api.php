<?php

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use App\Models\duel_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MailController;
use App\Http\Controllers\Api\ChatRoomController;
use App\Http\Controllers\Api\DuelController;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;

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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('change_password',  [UserController::class,'change_password']);


// Route::group(['middleware' => 'auth:api'], function () {

    Route::post('user-details', [UserController::class, 'userDetails']);
    Route::post('email/verification-notification', [UserController::class, 'sendVerificationEmail']);
    Route::get('verify-email/{id}/{hash}', [UserController::class, 'verify'])->name('verification.verify');

    Route::post('forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('reset-password', [UserController::class, 'reset']);

    Route::post('verify_otp', [UserController::class, 'verifyOtp']);
    Route::post('requestOtp',  [UserController::class,'requestOtp']);

    // Route::get('change-password', 'ChangePasswordController@index');
    // Route::post('change-password', 'ChangePasswordController@store')->name('change.password');

    Route::post('sendEmail', [MailController::class, 'sendEmail']);
    Route::post('sendEmailVerification', [MailController::class, 'sendEmailVerification']);


    Route::get('get_duel', [DuelController::class, 'get_duel']);
    Route::post('add_order', [DuelController::class, 'add_order']);

     Route::post('get_last_order_id', [DuelController::class, 'get_last_order_id']);
     Route::post('create_support_ticket', [DuelController::class, 'create_support_ticket']);


    Route::get('get_duel', [DuelController::class, 'get_duel']);

    Route::post('get_support_ticket_data', [DuelController::class, 'get_support_ticket_data']);

    Route::post('get_user_orders_data', [DuelController::class, 'get_user_orders_data']);

     

    
    //Route::post('change_password', [DuelController::class, 'change_password']);






    Route::controller(ChatRoomController::class)->group(function(){
        Route::group(['prefix' => 'chat'], function(){
            Route::get('chat-room', 'index');
            Route::post('add-chat-room', 'create');
            Route::post('add-user-in-chat-room', 'addUserInChatRoom');
            Route::post('send-user-msg-in-chat-room', 'sendUserMsgInChatRoom');
            Route::post('chat-room-list-by-id', 'chatRoomListById');

        });
    });


    //  Route::any('request_otp', [UserController::class, 'requestOtp']);

// });
