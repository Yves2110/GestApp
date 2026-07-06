<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PeriodeController;
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
Route::get('EditObjective/{id}', [ObjectiveController::class, 'objectiveedit'])->name('edit.objective');
Route::put('UpdateObjective', [ObjectiveController::class, 'objectiveupdate'])->name('ObjectiveUpdate');
// Route::resource('objective', ObjectiveController::class);

// route pour les sous objectif
Route::get('under_objective', [ObjectiveController::class, 'under_index'])->name('Under_Objective');
Route::post('StoreUnderObjective', [ObjectiveController::class, 'UnderObjectiveStore'])->name('UnderObjectiveStore');
Route::get('delete_under_objective/{id}', [ObjectiveController::class, 'destroye'])->name('delete.under_objective');

//route pour les trimestres
route::get('trimestre', [PeriodeController::class, 'view'])->name('trimestre');

// Route::get('activites', [ServiceController::class, 'index']);
// Route::get('objective', [ServiceController::class, 'text']); Route::get('under_objective', [ServiceController::class, 'texte']);

Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post')->middleware('throttle:5,1');

Route::get('registration', [AuthController::class, 'registration'])->name('register');

// Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('enregistrement', [AuthController::class,'postRegistration' ])->name('enregistrement')->middleware('throttle:3,1');

// Routes pour l'analytique et les statistiques
Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics')->middleware('auth');
Route::get('analytics/service/{serviceId}', [\App\Http\Controllers\AnalyticsController::class, 'getServiceStats'])->name('analytics.service')->middleware('auth');
Route::get('api/analytics/activities-by-service', [\App\Http\Controllers\AnalyticsController::class, 'getActivitiesByService'])->name('api.analytics.activities-by-service')->middleware('auth');
Route::get('api/analytics/monthly-evolution', [\App\Http\Controllers\AnalyticsController::class, 'getMonthlyEvolution'])->name('api.analytics.monthly-evolution')->middleware('auth');
Route::get('api/analytics/budget-by-service', [\App\Http\Controllers\AnalyticsController::class, 'getBudgetByService'])->name('api.analytics.budget-by-service')->middleware('auth');
Route::get('analytics/export', [\App\Http\Controllers\AnalyticsController::class, 'exportExcel'])->name('analytics.export')->middleware('auth');
Route::get('analytics/performance', [\App\Http\Controllers\AnalyticsController::class, 'performanceReport'])->name('analytics.performance')->middleware('auth');

// Routes pour les exports
Route::get('export', [\App\Http\Controllers\ExportController::class, 'exportConfig'])->name('export.config')->middleware('auth');
Route::post('export/process', [\App\Http\Controllers\ExportController::class, 'processExport'])->name('export.process')->middleware('auth');
Route::get('export/activities/csv', [\App\Http\Controllers\ExportController::class, 'exportActivitiesCSV'])->name('export.activities.csv')->middleware('auth');
Route::get('export/stats/csv', [\App\Http\Controllers\ExportController::class, 'exportGlobalStatsCSV'])->name('export.stats.csv')->middleware('auth');
Route::get('export/performance/csv', [\App\Http\Controllers\ExportController::class, 'exportPerformanceCSV'])->name('export.performance.csv')->middleware('auth');
Route::get('api/export/data', [\App\Http\Controllers\ExportController::class, 'getExportData'])->name('api.export.data')->middleware('auth');

// Routes pour le monitoring (admin uniquement)
Route::get('monitoring', [\App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring')->middleware('auth');
Route::get('api/health', [\App\Http\Controllers\MonitoringController::class, 'healthCheck'])->name('api.health')->middleware('auth');
Route::get('api/metrics', [\App\Http\Controllers\MonitoringController::class, 'getMetrics'])->name('api.metrics')->middleware('auth');
Route::get('api/logs', [\App\Http\Controllers\MonitoringController::class, 'getLogs'])->name('api.logs')->middleware('auth');

