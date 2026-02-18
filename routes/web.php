<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard;
use App\Livewire\VehicleList;
use App\Livewire\VehicleForm;
use App\Livewire\AppointmentCalendar;
use App\Livewire\AppointmentForm;
use App\Livewire\ClientList;
use App\Livewire\ClientForm;
use App\Livewire\ClientProfile;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Clients routes
    Route::get('/clients', ClientList::class)->name('clients.index');
    Route::get('/clients/create', ClientForm::class)->name('clients.create');
    Route::get('/clients/{clientId}/edit', ClientForm::class)->name('clients.edit');
    Route::get('/clients/{clientId}/profile', ClientProfile::class)->name('clients.profile');

    // Vehicles routes
    Route::get('/vehicles', VehicleList::class)->name('vehicles.index');
    Route::get('/vehicles/create', VehicleForm::class)->name('vehicles.create');
    Route::get('/vehicles/{vehicleId}/edit', VehicleForm::class)->name('vehicles.edit');

    // Appointments routes
    Route::get('/appointments', AppointmentCalendar::class)->name('appointments.index');
    Route::get('/appointments/create', AppointmentForm::class)->name('appointments.create');
    Route::get('/appointments/{appointmentId}/edit', AppointmentForm::class)->name('appointments.edit');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
