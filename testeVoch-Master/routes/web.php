<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FiltrosController;

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

Route::get('/', 'FiltrosController@index')->name('index');

Route::prefix('/filtros')->group(function () {
    Route::post('/', 'FiltrosController@filtros')->name('filtros');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
