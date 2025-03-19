<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification
Auth::routes();

// Route du tableau de bord
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes des cours
Route::resource('cours', CoursController::class);
Route::post('cours/check-conflit', [CoursController::class, 'checkConflit'])->name('cours.check-conflit');

// Routes des émargements
Route::resource('emargements', EmargementController::class);
Route::post('emargements/{emargement}/valider', [EmargementController::class, 'valider'])->name('emargements.valider');
Route::post('emargements/check-conflit', [EmargementController::class, 'checkConflit'])->name('emargements.check-conflit');

// Routes d'administration
Route::prefix('admin')->name('admin.')->group(function () {
    // Routes des utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Routes des rapports
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [RapportController::class, 'index'])->name('index');
        Route::get('/presences', [RapportController::class, 'presences'])->name('presences');
        Route::get('/export-pdf', [RapportController::class, 'exportPDF'])->name('export-pdf');
        Route::get('/export-excel', [RapportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-presences', [RapportController::class, 'exportPresences'])->name('export-presences');
    });
});

// Routes des salles
Route::resource('salles', SalleController::class);

// Routes des notifications
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
});
