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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

//     // Route::post('login', 'LoginController@login')->name('login');

//     // Route::post('register', 'RegisterController@register')->name('register');

//     Route::post('/login', 'LoginController@login');
//     Route::post('/register', 'RegisterController@register');
// });
Route::post('/send-reset', 'User\WalletController@SendReset');


Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/send-invite', 'MailController@SendInvite');
    Route::post('/send-reset', 'MailController@SendReset');
    Route::post('/send-instructions', 'MailController@SendInstructions');

    Route::post('/logout', 'Auth\LogoutController@logout');

    Route::post('/pay-bill', 'Transactions\BillsPaymentController@PayBill');
    Route::get('/biller-companies', 'Transactions\BillsPaymentController@GetComp');

    Route::post('/cash-in', 'Transactions\CashInOutController@CashInOut');
    Route::post('/cash-out', 'Transactions\CashInOutController@CashInOut');

    Route::post('/send-funds', 'Transactions\SendReqController@SendRequest');
    Route::post('/request-funds', 'Transactions\SendReqController@SendRequest');
    Route::post('/accept-request', 'Transactions\SendReqController@AcceptRequest');
    Route::post('/deny-request', 'Transactions\SendReqController@DenyRequest');

    Route::get('/wallet','User\WalletController@wallet');
    Route::post('/link-wallet','User\WalletController@LinkWallet');
    Route::post('/check-email', 'User\WalletController@CheckEmail');
    Route::post('/check-account', 'User\WalletController@CheckAccount');

    Route::post('/convert','Transactions\ConvertController@convert');

    Route::get('/pending-transactions', 'Transactions\HistoryController@pending');
    Route::post('/view-transaction', 'Transactions\HistoryController@view');
    Route::post('/cancel-transaction', 'Transactions\HistoryController@cancel');
    Route::get('/transactions', 'Transactions\HistoryController@index');

    Route::get('/download', 'DownloadController@export');
    Route::get('/profile','ProfileController@profile');
});


