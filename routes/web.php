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

Auth::routes();

Route::get('/home', 'HomeController@home')->name('home'); 

Route::get('/transactions-view', 'DownloadController@index2');
Route::get('/download-pdf', 'DownloadController@exportToPDF');
Route::post('/download-with-date-pdf', 'DownloadController@exportWithDateToPDF');

Route::get('/user/verify/{token}', 'VerifyController@verifyUser');

Route::post('/login', 'Auth\LoginController@login');
Route::get('/login/{message}', 'PagesController@view');
// Route::post('/register', 'Auth\RegisterController@register');

//Redirect if an unknown route is being accessed
Route::get('{any}', function () {
    return redirect('home');
})->where('any','.*');