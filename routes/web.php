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


Route::get('service', [HomeController::class, 'serviceajout'])->name('services')->middleware('auth');
Route::post('ajoutservice', [HomeController::class, 'servicestore'])->name('ajoutservice')->middleware('auth');

/*route pour le Guide */
Route::post('guide',[GuideController::class, 'store'])->name('guide')->middleware('auth');
Route::get('delete/{id}', [GuideController::class, 'destroy'])->name('delete.guide')->middleware('auth');
Route::get('Guide',[GuideController::class, 'index'])->name('Guide')->middleware('auth');

Route::get('home', [HomeController::class, 'index'])->name('Home')->middleware('auth');

 Route::get('/',[AuthController::class, 'index'])->name('login');

// route pour les activites
Route::get('activites', [ActivitiesController::class, 'index'])->name('Activites')->middleware('auth');
Route::post('ActivitiesStore', [ActivitiesController::class, 'ActivitiesStore'])->name('ActivitiesStore')->middleware('auth');
Route::get('activites/{id}/edit', [ActivitiesController::class, 'edit'])->name('activites.edit')->middleware('auth');
Route::put('activites/{id}', [ActivitiesController::class, 'update'])->name('activites.update')->middleware('auth');
Route::delete('activites/{id}', [ActivitiesController::class, 'destroy'])->name('activites.destroy')->middleware('auth');
Route::get('activites/{id}', [ActivitiesController::class, 'show'])->name('activites.show')->middleware('auth');
Route::get('activites-kanban', [ActivitiesController::class, 'kanban'])->name('activites.kanban')->middleware('auth');

// Workflow transitions
Route::post('activites/{id}/workflow', [\App\Http\Controllers\WorkflowController::class, 'transition'])->name('activites.workflow')->middleware('auth');

// Notifications
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationsController::class, 'index'])->name('index');
    Route::post('{id}/read', [\App\Http\Controllers\NotificationsController::class, 'markRead'])->name('read');
    Route::post('read-all', [\App\Http\Controllers\NotificationsController::class, 'markAllRead'])->name('read-all');
    Route::get('count', [\App\Http\Controllers\NotificationsController::class, 'unreadCount'])->name('count');
});

// route pour les objectifs
Route::get('objective', [ObjectiveController::class, 'index'])->name('Objective')->middleware('auth');
Route::post('StoreObjective', [ObjectiveController::class, 'ObjectiveStore'])->name('ObjectiveStore')->middleware('auth');
Route::get('delete_objective/{id}', [ObjectiveController::class, 'destroy'])->name('delete.objective')->middleware('auth');
Route::get('EditObjective/{id}', [ObjectiveController::class, 'objectiveedit'])->name('edit.objective')->middleware('auth');
Route::put('UpdateObjective/{id}', [ObjectiveController::class, 'objectiveupdate'])->name('ObjectiveUpdate')->middleware('auth');
// Route::resource('objective', ObjectiveController::class);

// route pour les sous objectif
Route::get('under_objective', [ObjectiveController::class, 'under_index'])->name('Under_Objective')->middleware('auth');
Route::post('StoreUnderObjective', [ObjectiveController::class, 'UnderObjectiveStore'])->name('UnderObjectiveStore')->middleware('auth');
Route::get('delete_under_objective/{id}', [ObjectiveController::class, 'destroye'])->name('delete.under_objective')->middleware('auth');

//route pour les trimestres
Route::get('trimestre', [PeriodeController::class, 'view'])->name('trimestre')->middleware('auth');
Route::post('trimestre', [PeriodeController::class, 'store'])->name('trimestre.store')->middleware('auth');

// Route::get('activites', [ServiceController::class, 'index']);
// Route::get('objective', [ServiceController::class, 'text']); Route::get('under_objective', [ServiceController::class, 'texte']);

Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post')->middleware('throttle:5,1');

Route::get('registration', [AuthController::class, 'registration'])->name('register')->middleware(['auth', 'superadmin']);

// Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('enregistrement', [AuthController::class,'postRegistration' ])->name('enregistrement')->middleware(['auth', 'superadmin', 'throttle:3,1']);

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

// Routes des paramètres (Super Administrateur uniquement)
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('parametres', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');

    // Gestion des utilisateurs
    Route::get('parametres/utilisateurs', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('settings.users');
    Route::post('parametres/utilisateurs/{id}/toggle', [\App\Http\Controllers\UserManagementController::class, 'toggleActive'])->name('settings.users.toggle');
    Route::post('parametres/utilisateurs/{id}/reset', [\App\Http\Controllers\UserManagementController::class, 'resetCredentials'])->name('settings.users.reset');

    // Gestion des permissions par rôle
    Route::get('parametres/permissions', [\App\Http\Controllers\PermissionController::class, 'index'])->name('settings.permissions');
    Route::put('parametres/permissions/{roleId}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('settings.permissions.update');

    // Journal d'audit
    Route::get('parametres/audit', [\App\Http\Controllers\AuditController::class, 'index'])->name('settings.audit');
});

// Routes pour le monitoring (admin uniquement)
Route::get('monitoring', [\App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring')->middleware('auth');
Route::get('api/health', [\App\Http\Controllers\MonitoringController::class, 'healthCheck'])->name('api.health')->middleware('auth');
Route::get('api/metrics', [\App\Http\Controllers\MonitoringController::class, 'getMetrics'])->name('api.metrics')->middleware('auth');
Route::get('api/logs', [\App\Http\Controllers\MonitoringController::class, 'getLogs'])->name('api.logs')->middleware('auth');

// Routes Calendrier
Route::get('activites-calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('activites.calendar')->middleware('auth');
Route::get('api/calendar/events', [\App\Http\Controllers\CalendarController::class, 'events'])->name('api.calendar.events')->middleware('auth');

// Routes Timeline
Route::get('activites-timeline', [\App\Http\Controllers\TimelineController::class, 'index'])->name('activites.timeline')->middleware('auth');
Route::get('api/timeline/data', [\App\Http\Controllers\TimelineController::class, 'data'])->name('api.timeline.data')->middleware('auth');

// Routes Export PDF
Route::get('export/pdf/activities', [\App\Http\Controllers\PdfController::class, 'activitiesReport'])->name('export.pdf.activities')->middleware('auth');
Route::get('export/pdf/performance', [\App\Http\Controllers\PdfController::class, 'performanceReport'])->name('export.pdf.performance')->middleware('auth');

// Recherche globale
Route::get('api/search', \App\Http\Controllers\SearchController::class)->name('api.search')->middleware('auth');

// Styleguide (dev only)
if (app()->isLocal()) {
    Route::get('styleguide', function () {
        return view('pages.styleguide');
    })->name('styleguide');
}

