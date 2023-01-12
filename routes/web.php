<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
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

// Route::get('/guide', function () {
//     return view('pages.guide');
// });
// Route::get('/home', function () {
//     return view('layouts.home');
// });
// Route::get('/login', function () {
//     return view('auth.login');
// });
// Route::get('/register', function () {
//     return view('auth.register');
// });
Route::get('service', [HomeController::class, 'serviceajout'])->name('services');
Route::post('ajoutservice', [HomeController::class, 'servicestore'])->name('ajoutservice');

/*route pour Authentification */
Route::post('guide',[GuideController::class, 'store'])->name('guide');
Route::get('delete/{id}', [GuideController::class, 'destroy'])->name('delete.guide');
Route::get('Guide',[GuideController::class, 'index'])->name('Guide');

Route::get('home', [HomeController::class, 'index'])->name('Home');

Route::get('/', [AuthController::class, 'index']);

Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::get('registration', [AuthController::class, 'registration'])->name('register');

Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

/*middleware*/

// Route::middleware(['auth', 'user-access:SuperAdmin'])->group(function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');

// });

// Route::middleware(['auth', 'user-access:president'])->group(function () {
//     Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
// });

// Route::middleware(['auth', 'user-access:admin'])->group(function () {
//     Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
// });

// Route::middleware(['auth', 'user-access:services'])->group(function () {
//     Route::get('/service/home', [HomeController::class, 'adminHome'])->name('service.home');
// });
