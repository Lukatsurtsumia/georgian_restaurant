<?php

use App\Http\Controllers\GoogleBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ManageReservationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\DashboardOverview;
use App\Livewire\Admin\MenuItemManager;
use App\Livewire\Admin\ReservationManager;
use App\Livewire\Admin\SpecialManager;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::get('/reservations/{token}', [ManageReservationController::class, 'show'])->name('reservations.manage');
Route::post('/reservations/{token}/cancel', [ManageReservationController::class, 'cancel'])->name('reservations.cancel');

// "Continue with Google" booking shortcut — see GoogleBookingController for why
// this is unrelated to the admin auth system.
Route::get('/auth/google/redirect', [GoogleBookingController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleBookingController::class, 'callback'])->name('google.callback');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardOverview::class)->name('dashboard');
    Route::get('/reservations', ReservationManager::class)->name('reservations');
    Route::get('/menu-items', MenuItemManager::class)->name('menu-items');
    Route::get('/specials', SpecialManager::class)->name('specials');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
