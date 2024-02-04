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
*/
// Route::get('/{filter?}', [App\Http\Controllers\BerandaController::class, 'index'])->name('index');

Route::get('/', [App\Http\Controllers\BerandaController::class, 'index'])->name('index');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::get('/datatransaction', [App\Http\Controllers\AdminController::class, 'getDatatransaction'])->name('datatransaction');
    Route::get('/datatable', [App\Http\Controllers\AdminController::class, 'getDatatable'])->name('datatable');
    Route::post('/fetch-ratios', [App\Http\Controllers\AdminController::class, 'fetchAndSaveRatios'])->name('fetch-ratios');
    Route::post('/deleteData', [App\Http\Controllers\AdminController::class, 'deleteData'])->name('deleteData');
    Route::post('/deleteSelected', [App\Http\Controllers\AdminController::class, 'deleteSelected'])->name('deleteSelected');
    
});
