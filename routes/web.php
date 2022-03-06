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
Route::get("/logout","HomeController@logout")->name("logout");
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// Here is all subscription route
Route::get('createAllProducts', 'StripeSubscriptionController@createAllProducts')->name('createAllProducts')->middleware('auth');

Route::get('listProducts', 'StripeSubscriptionController@listAllProducts')->name('listProducts')->middleware('auth');

Route::post('subscriptionPost', 'StripeSubscriptionController@subscriptionPost')->middleware('auth');

Route::get('/subscription', 'StripeSubscriptionController@subscription')->name('subscription')->middleware('auth');
Route::get('/editSubscription/{subscription_id}/{item_id}/{quantity}', 'StripeSubscriptionController@editSubscription')->name('editSubscription')->middleware('auth');
Route::post('updateSubscription', 'StripeSubscriptionController@updateSubscription')->name('updateSubscription')->middleware('auth');



