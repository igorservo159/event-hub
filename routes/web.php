<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/events/myEvents', [EventController::class, 'myEvents'])
    ->name('events.myEvents')
    ->middleware(['auth', 'verified']);
    
Route::get('/registrations/{event}', [RegistrationController::class, 'registeredsList'])
    ->name('registrations.registeredsList')
    ->middleware(['auth', 'verified']);

Route::post('/registrations/{event}', [RegistrationController::class, 'store'])
    ->name('registrations.store')
    ->middleware(['auth', 'verified']);

Route::post('/registrations/approvePayment/{event}', [RegistrationController::class, 'approvePayment'])
    ->name('registrations.approvePayment')
    ->middleware(['auth', 'verified']);
    
Route::get('/payments/{registration}', [PaymentController::class, 'create'])
    ->name('payments.create')
    ->middleware(['auth', 'verified']);

Route::post('/payments/{registration}', [PaymentController::class, 'store'])
    ->name('payments.store')
    ->middleware(['auth', 'verified']);

Route::match(['get', 'post'], '/payments/reembolso/{registration}', [PaymentController::class, 'reembolso'])
    ->name('payments.reembolso')
    ->middleware(['auth', 'verified']);

Route::resource('events', EventController::class)
    ->only(['index', 'store', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
    ->middleware(['auth', 'verified']);

Route::resource('registrations', RegistrationController::class)
    ->only(['index', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('payments', PaymentController::class)
    ->only(['index','destroy'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
