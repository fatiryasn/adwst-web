<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController;

use App\Http\Controllers\Public\DestinationController as PublicDestinationController;
use App\Http\Controllers\Public\TicketController as PublicTicketController;
use App\Http\Controllers\Public\AffiliateController as PublicAffiliateController;
use App\Models\Destination;

//public landing page
Route::get('/', function () {
    $destinations = Destination::where('status', 'active')
        ->latest()
        ->take(4)
        ->get();

    return view('landing.home', compact('destinations'));
});
Route::get('/destinasi', [PublicDestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinasi/{slug}', [PublicDestinationController::class, 'show'])->name('destinations.show');

Route::get('/destinasi/{slug}/tiket', [PublicTicketController::class, 'create'])->name('destinations.tickets.create');
Route::post('/destinasi/{slug}/tiket', [PublicTicketController::class, 'store'])->name('destinations.tickets.store');
Route::get('/destinasi/{slug}/tiket/sukses', [PublicTicketController::class, 'success'])
    ->name('destinations.tickets.success');

Route::get('/afiliasi', [PublicAffiliateController::class, 'index'])->name('affiliates.index');
Route::post('/afiliasi', [PublicAffiliateController::class, 'store'])->name('affiliates.store');
Route::get('/afiliasi/sukses', [PublicAffiliateController::class, 'success'])->name('affiliates.success');
Route::get('/afiliasi/cek-poin', [PublicAffiliateController::class, 'checkForm'])->name('affiliates.check');
Route::post('/afiliasi/cek-poin', [PublicAffiliateController::class, 'check'])->name('affiliates.check.submit');

//admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//protected routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/destination', [DestinationController::class, 'index'])->name('destination.index');
    Route::get('/destination/new', [DestinationController::class, 'new'])->name('destination.new');
    Route::post('/destination', [DestinationController::class, 'store'])->name('destination.store');
    Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destination.show');
    Route::put('/destination/{id}', [DestinationController::class, 'update'])->name('destination.update');
    Route::delete('/destination/{id}', [DestinationController::class, 'destroy'])->name('destination.destroy');

    Route::post('/destination/{id}/cottages', [DestinationController::class, 'addCottage'])->name('destination.addCottage');
    Route::delete('/destination/{id}/cottages/{cottageId}', [DestinationController::class, 'deleteCottage'])->name('destination.deleteCottage');

    Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
    Route::get('/affiliate/{id}', [AffiliateController::class, 'show'])->name('affiliate.show');

    Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
    Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
    Route::post('/ticket/{id}/update-status', [TicketController::class, 'updateStatus'])->name('ticket.updateStatus');
    Route::post('/ticket/{id}/check-in', [TicketController::class, 'checkIn'])->name('ticket.checkIn');
    Route::post('/ticket/{id}/update-notes', [TicketController::class, 'updateNotes'])->name('ticket.updateNotes');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/rotate-api-key', [SettingsController::class, 'rotateApiKey'])->name('settings.rotate-api-key');
});
