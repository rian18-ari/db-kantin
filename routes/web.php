<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RfidRegistrationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('admin/rfid')->group(function () {
    Route::get('/registration', [RfidRegistrationController::class, 'index'])->name('admin.rfid.registration');
    Route::post('/registration/toggle', [RfidRegistrationController::class, 'toggleMode'])->name('admin.rfid.toggle-mode');
    Route::post('/registration/assign/{rfidCard}', [RfidRegistrationController::class, 'assign'])->name('admin.rfid.assign');
    Route::post('/user/store', [RfidRegistrationController::class, 'storeUser'])->name('admin.user.store');
});
