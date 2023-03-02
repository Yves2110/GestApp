<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Notifications\Gestapp;
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


Route::get('service', [HomeController::class, 'serviceajout'])->name('services');
Route::post('ajoutservice', [HomeController::class, 'servicestore'])->name('ajoutservice');

/*route pour le Guide */
Route::post('guide',[GuideController::class, 'store'])->name('guide');
Route::get('delete/{id}', [GuideController::class, 'destroy'])->name('delete.guide');
Route::get('Guide',[GuideController::class, 'index'])->name('Guide');

Route::get('home', [HomeController::class, 'index'])->name('Home');

 Route::get('/',[AuthController::class, 'index']);
// route pour les activites
Route::get('activites', [ActivitiesController::class, 'index'])->name('Activites');
Route::post('ActivitiesStore', [ActivitiesController::class, 'ActivitiesStore'])->name('ActivitiesStore');


// route pour les objectifs
Route::get('objective', [ObjectiveController::class, 'index'])->name('Objective');
Route::post('StoreObjective', [ObjectiveController::class, 'ObjectiveStore'])->name('ObjectiveStore');
Route::get('delete_objective/{id}', [ObjectiveController::class, 'destroy'])->name('delete.objective');
Route::get('EditObjective/{objective}', [ObjectiveController::class, 'objectiveedit'])->name('edit.objective');
Route::put('UpdateObjective', [ObjectiveController::class, 'objectiveupdate'])->name('ObjectiveUpdate');
// Route::resource('objective', ObjectiveController::class);

// route pour les sous objectif
Route::get('under_objective', [ObjectiveController::class, 'under_index'])->name('Under_Objective');
Route::post('StoreUnderObjective', [ObjectiveController::class, 'UnderObjectiveStore'])->name('UnderObjectiveStore');
Route::get('delete_under_objective/{id}', [ObjectiveController::class, 'destroye'])->name('delete.under_objective');

// Route::get('activites', [ServiceController::class, 'index']);
// Route::get('objective', [ServiceController::class, 'text']); Route::get('under_objective', [ServiceController::class, 'texte']);

Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::get('registration', [AuthController::class, 'registration'])->name('register');

// Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('enregistrement', [AuthController::class,'postRegistration' ])->name('enregistrement');

