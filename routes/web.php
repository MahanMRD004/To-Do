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

//Main Routes
Route::get('/', [\App\Http\Controllers\PagesController::class,'index'])->name('index');

Route::get('/MyDay', [\App\Http\Controllers\PagesController::class,'myDay'])->name('myDay');

Route::get('/All', [\App\Http\Controllers\PagesController::class,'all'])->name('all');

Route::get('/Complete', [\App\Http\Controllers\PagesController::class,'complete'])->name('complete');

Route::get('/list/{id}', [\App\Http\Controllers\PagesController::class,'list'])->name('list');

//Authentication
Route::get('/signup',[\App\Http\Controllers\UsersController::class,'signup'])->name('signup');
Route::post('/signupPost',[\App\Http\Controllers\UsersController::class,'signupPost'])->name('signupPost');

Route::get('/login',[\App\Http\Controllers\UsersController::class,'login'])->name('login');
Route::post('/loginPost',[\App\Http\Controllers\UsersController::class,'loginPost'])->name('loginPost');

Route::get('/forgot',[\App\Http\Controllers\UsersController::class,'forgot'])->name('forgot');
Route::post('/forgotPost',[\App\Http\Controllers\UsersController::class,'forgotPost'])->name('forgotPost');

Route::get('/resetPassword/{token}',[\App\Http\Controllers\UsersController::class,'resetPassword'])->middleware('guest')->name('resetPassword');
Route::post('/resetPasswordPost',[\App\Http\Controllers\UsersController::class,'resetPasswordPost'])->name('resetPasswordPost');

Route::post('/logout',[\App\Http\Controllers\UsersController::class,'logout'])->name('logout');

Route::post('/editInfo', [\App\Http\Controllers\UsersController::class,'editInfo'])->name('editInfo');


//Lists & Tasks Handlers
Route::post('/addTask', [\App\Http\Controllers\TasksController::class,'addTask'])->name('addTask');

Route::post('/removeTask', [\App\Http\Controllers\TasksController::class,'removeTask'])->name('removeTask');

Route::post('/check', [\App\Http\Controllers\TasksController::class,'check'])->name('check');

Route::post('/setPriority', [\App\Http\Controllers\TasksController::class,'setPriority'])->name('setPriority');

Route::post('/addList', [\App\Http\Controllers\ListsController::class,'addList'])->name('addList');

Route::post('/removeList', [\App\Http\Controllers\ListsController::class,'removeList'])->name('removeList');


// Themes
Route::post('/setTheme', [\App\Http\Controllers\ListsController::class,'setTheme'])->name('setTheme');


// Upload
Route::post('/store', [\App\Http\Controllers\UploadController::class, 'store'])->name('store');

Route::get('/test',[\App\Http\Controllers\PagesController::class,'test'])->name('test');
