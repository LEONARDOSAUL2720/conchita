<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return redirect('/home');
// });

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/productos', 'App\Http\Controllers\ProductoController')
    ->except(['show'])
    ->middleware('auth');


Route::get('delete-producto/{producto_id}',[
     'as' => 'delete-producto',
     'middleware' => 'auth',
     'uses'=> 'App\Http\Controllers\ProductoController@delete_producto'
]);
    











// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::resource('/productos', 'App\Http\Controllers\ProductoController')
//     ->except(['show'])
//     ->middleware('auth');

// Route::get('delete-producto/{producto_id}', [
//         // nombre interno para mandar a llamar a la funcion delete
//         'as'=>'delete-producto', 
//         'middleware'=> 'auth',
//         'uses' => 'App\Http\Controllers\ProductoController@delete_producto'
// ]);

// Route::get('update-producto/{producto_id}', [
//     // nombre interno para mandar a llamar a la funcion delete
//     'as'=>'update-producto', 
//     'middleware'=> 'auth',
//     'uses' => 'App\Http\Controllers\ProductoController@update_producto'
// ]);

// Route::put('update/{id}', [
//     // nombre interno para mandar a llamar a la funcion delete
//     'as'=>'update', 
//     'middleware'=> 'auth',
//     'uses' => 'App\Http\Controllers\ProductoController@update'
// ]);