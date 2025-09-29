<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrgDashboardController;
use App\Http\Controllers\OrgPetController;
use App\Http\Controllers\OrgAdoptionApplicationController;
use App\Http\Controllers\OrganizationDirectoryController;
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

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::view('/features', 'features')->name('features');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Directorio público de organizaciones (visible para adoptantes y público)
Route::get('/orgs', [OrganizationDirectoryController::class, 'index'])->name('orgs.index');
Route::get('/orgs/{organization}', [OrganizationDirectoryController::class, 'show'])->name('orgs.details');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard y recursos de Organizaciones
    Route::prefix('orgs')->as('orgs.')->middleware('role:organizacion,admin')->group(function () {
        Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');

        // Mascotas de la organización
        Route::resource('pets', OrgPetController::class)->parameters([
            'pets' => 'pet',
        ]);

        // Solicitudes de adopción que pertenecen a la organización
        Route::resource('adoptions', OrgAdoptionApplicationController::class)->parameters([
            'adoptions' => 'adoption',
        ]);
    });

    // Zona adoptantes (dashboard y solicitudes propias)
    Route::prefix('adoptions')->as('adoptions.')->middleware('role:adoptante,admin')->group(function () {
        // Vistas ya existentes en resources/views/adoptions/*.blade.php (index, dashboard, details)
        Route::view('/', 'adoptions.dashboard')->name('dashboard');
        Route::view('/mine', 'adoptions.index')->name('index');
        Route::view('/{id}', 'adoptions.details')->name('details');
    });
});

require __DIR__.'/auth.php';
