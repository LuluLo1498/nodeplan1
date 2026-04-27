<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Dashboard principal
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NOTAS / APUNTES
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/crear', [NoteController::class, 'create'])->name('notes.create');
    Route::get('/notes/editar/{id}', [NoteController::class, 'create'])->name('notes.edit');
    Route::post('/notes/upsert', [NoteController::class, 'upsert'])->name('notes.upsert');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // ASIGNATURAS
    Route::get('/asignaturas', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/asignaturas', [SubjectController::class, 'store'])->name('subjects.store');
    Route::delete('/asignaturas/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // EVENTOS / CALENDARIO
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    Route::get('/calendario', function () {
        return view('dashboard'); 
    })->name('calendario');

});

require __DIR__.'/auth.php';