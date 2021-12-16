<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Auth;
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

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', [WebhookController::class, 'index'])->name('home');
Route::post('/set-end-point', [WebhookController::class, 'setEndPoint'])->name('setEndPoint');
Route::post('/test-webhook', [WebhookController::class, 'testWebhook'])->name('testWebhook');
//Route::post('/webhook/test-success', [WebhookController::class, 'testSuccess'])->name('testSuccess');
Route::get('/view', [WebhookController::class, 'view'])->name('view');
Route::get('/alert', [WebhookController::class, 'alert'])->name('alert');

//Route::post('/set-end-point', 'HomeController@setEndPoint')->name('setEndPoint');

//Route::post('/test-webhook', 'HomeController@testWebhook')->name('testWebhook');

//Route::post('/webhook/test-success', 'TestHooksController@testSuccess');


Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
