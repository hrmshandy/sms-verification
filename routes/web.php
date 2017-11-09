<?php

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

Route::redirect('/', '/v1');

Route::prefix('v1')->group(function(){
    Route::get('/', 'VerificationV1Controller@index');
    Route::get('/verify/{code}', 'VerificationV1Controller@verify');
    Route::post('/', 'VerificationV1Controller@store');
});

Route::prefix('v2')->group(function(){
    Route::get('/', 'VerificationV2Controller@index');
    Route::get('/verify', 'VerificationV2Controller@verifyForm');
    Route::post('/', 'VerificationV2Controller@store');
    Route::post('/verify', 'VerificationV2Controller@verify');
});

Route::get('/ty', 'VerificationController@thankYou');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
