<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\MyFileController;
use App\Http\Controllers\SharedFileController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/myFiles', [MyFileController::class, 'index'])->name('myFiles');
Route::post('/myFiles', [MyFileController::class, 'store'])->name('storeFiles');

Route::get('myFiles/delete/{id}', [MyFileController::class, 'delete']);
Route::get('myFiles/download/{id}', [MyFileController::class, 'download']);
Route::post('myFiles/edit', [MyFileController::class, 'edit'])->name('editFile');

Route::get('/sharedFile', [SharedFileController::class, 'index'])->name('sharedFile');;
Route::get('/sharedFile/download/{id}', [SharedFileController::class, 'download']);

Route::get('/contact', [ContactController::class, 'index'])->name('contacts');;
Route::post('/contact/add-contact', [ContactController::class, 'addContact'])->name('contact.add');
Route::get('/contact/{id}/accept-contact', [ContactController::class, 'acceptContact'])->name('contact.accept');
Route::get('/contact/{id}/decline-contact', [ContactController::class, 'declineContact'])->name('contact.decline');
Route::post('/contact/delete/{id}', [ContactController::class, 'deleteContact'])->name('contact.delete');

Route::post('/myFiles/sendFile', [SharedFileController::class, 'sendFile'])->name('sendFile');
