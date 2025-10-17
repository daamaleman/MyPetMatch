<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdoptionApplicationController;
use App\Http\Controllers\OrgDashboardController;
use App\Http\Controllers\OrgPetController;
use App\Http\Controllers\OrgAdoptionApplicationController;
use App\Http\Controllers\OrganizationDirectoryController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SendEmailController;
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

// Local-only: simple SMTP test endpoint
if (app()->environment('local', 'testing')) {
    Route::get('/test-mail', [SendEmailController::class, 'test']);
}

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::view('/features', 'features')->name('features');
Route::view('/contact', 'contact')->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Directorio público de organizaciones (visible para adoptantes y público)
Route::get('/orgs', [OrganizationDirectoryController::class, 'index'])->name('orgs.index');
Route::get('/orgs/{organization}', [OrganizationDirectoryController::class, 'show'])
    ->whereNumber('organization')
    ->name('orgs.details');

// Detalle público de mascota (visible para adoptantes y público)
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.details');

// Listado público de mascotas disponibles para adopción
Route::view('/adoptar', 'adoptions.index')->name('adoptions.browse');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/organization', [ProfileController::class, 'updateOrganization'])->name('profile.organization.update');
    Route::patch('/profile/adopter', [ProfileController::class, 'updateAdopter'])->name('profile.adopter.update');
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
        // Listado de mascotas disponibles (index)
        Route::view('/mine', 'adoptions.index')->name('index');
        // Iniciar solicitud de adopción (form)
        Route::get('/apply/{pet}', [AdoptionApplicationController::class, 'apply'])
            ->name('apply');

        // Enviar solicitud de adopción
        Route::post('/', [AdoptionApplicationController::class, 'store'])
            ->name('store');

        // Detalle de solicitud (placeholder aún)
        Route::view('/{id}', 'adoptions.details')
            ->whereNumber('id')
            ->name('details');
    });

    // Submissions: listado/gestión para ambos roles (adoptante y organización)
    Route::prefix('submissions')->as('submissions.')->group(function(){
        // Listado: detecta rol en el controlador
        Route::get('/', [AdoptionApplicationController::class, 'index'])->name('index');
        // Detalle
        Route::get('/{application}', [AdoptionApplicationController::class, 'show'])->name('show');
        // Editar (solo adoptantes dueños de la solicitud y en estado editable)
        Route::get('/{application}/edit', [AdoptionApplicationController::class, 'edit'])->name('edit');
        // Update (adoptante: mensaje/answers; org: estado)
        Route::put('/{application}', [AdoptionApplicationController::class, 'update'])->name('update');
    });
});

require __DIR__ . '/auth.php';
