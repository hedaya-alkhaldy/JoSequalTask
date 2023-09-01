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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::resource('kmls', App\Http\Controllers\KMLController::class);


Route::get('/checkKml', [App\Http\Controllers\KMLController::class, 'checkKmlFile'])->name('check.Kmls');


// Route::get('/checkKml/action', [App\Http\Controllers\KMLControlle::class, 'checkKmlFile'])->name('check.Kmls');

Route::post('/ajax_upload/action', [App\Http\Controllers\KMLController::class, 'action'])->name('ajaxupload.action');
Route::post('/test/{id}', [App\Http\Controllers\KMLController::class, 'test'])->name('test.action');



Route::get('/map', function () {
    return view('k_m_l_s.map');
})->name('map');

