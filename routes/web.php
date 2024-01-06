<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerMenu;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| <!-- © 2020 Copyright: Tahu Coding -->
*/

Route::get('/form', [CustomerMenu::class, 'showForm']);
Route::post('/form', [CustomerMenu::class, 'processForm']);
Route::get('/kalahaMenu', 'CustomerMenu@index')->name('kalaha');
Route::post('/kalahaMenu/addtocart/{customer_id}/{product_id}', 'CustomerMenu@addProductCart')->name('add.to.cart');
Route::get('/kalahaMenu/cart/{customer_id}', 'CustomerMenu@CartView')->name('CartList');
Route::post('/kalahaMenu/bayar/{customer_id}', 'CustomerMenu@bayar')->name('payment');
Route::post('/kalahaMenu/increasecart/{customer_id}/{rowId}', 'CustomerMenu@increasecart')->name('increasecart');
Route::post('/kalahaMenu/decreasecart/{customer_id}/{rowId}', 'CustomerMenu@decreasecart')->name('decreasecart');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {  
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/products','ProductController');
    //sorry kalau ada typo penggunaan bahasa inggris krn saya orang indonesia yang mencoba belajar b.inggris
    Route::get('/transcation', 'TransactionController@index');
    Route::post('/transcation/addproduct/{id}', 'TransactionController@addProductCart');
    Route::post('/transcation/removeproduct/{id}', 'TransactionController@removeProductCart');
    Route::post('/transcation/clear', 'TransactionController@clear');
    Route::post('/transcation/increasecart/{id}', 'TransactionController@increasecart');
    Route::post('/transcation/decreasecart/{id}', 'TransactionController@decreasecart');
    Route::post('/transcation/bayar','TransactionController@bayar');
    Route::get('/transcation/history','TransactionController@history');
    Route::get('/transcation/customer_history','CustomerMenu@history');
    Route::get('/transcation/laporan_customer/{id}','CustomerMenu@laporan');
    Route::get('/transcation/laporan/{id}','TransactionController@laporan');
});




