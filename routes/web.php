<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RefundController;
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

Route::get('/permissionRequests/index', [PermissionRequestController::class, 'index'])
    ->name('permissionRequests.index')
    ->middleware(['auth', 'verified']);

Route::post('/permissionRequests/approve/{permissionrequest}', [PermissionRequestController::class, 'approve'])
    ->name('permissionRequests.approve')
    ->middleware(['auth', 'verified']);

Route::post('/permissionRequests/deny/{permissionrequest}', [PermissionRequestController::class, 'deny'])
    ->name('permissionRequests.deny')
    ->middleware(['auth', 'verified']);

Route::get('/events/myEvents', [EventController::class, 'myEvents'])
    ->name('events.myEvents')
    ->middleware(['auth', 'verified']);

Route::post('/registrations/{event}', [RegistrationController::class, 'store'])
    ->name('registrations.store')
    ->middleware(['auth', 'verified']);
    
Route::get('/registrations/listRegisters/{event}', [RegistrationController::class, 'listRegisters'])
    ->name('registrations.listRegisters')
    ->middleware(['auth', 'verified']);

Route::get('/payments/{registration}', [PaymentController::class, 'create'])
    ->name('payments.create')
    ->middleware(['auth', 'verified']);

Route::post('/payments/{registration}', [PaymentController::class, 'store'])
    ->name('payments.store')
    ->middleware(['auth', 'verified']);

Route::post('/payments/approvePayment/{registration}', [PaymentController::class, 'approvePayment'])
    ->name('payments.approvePayment')
    ->middleware(['auth', 'verified']);

Route::post('/payments/denyPayment/{registration}', [PaymentController::class, 'denyPayment'])
    ->name('payments.denyPayment')
    ->middleware(['auth', 'verified']);

Route::post('/refunds/{registration}', [RefundController::class, 'store'])
    ->name('refunds.store')
    ->middleware(['auth', 'verified']);

Route::get('/refunds/listRefunds/{event}', [RefundController::class, 'listRefunds'])
    ->name('refunds.listRefunds')
    ->middleware(['auth', 'verified']);
    
Route::match(['get', 'post'], '/refunds/askForRefund/{registration}', [RefundController::class, 'askForRefund'])
    ->name('refunds.askForRefund')
    ->middleware(['auth', 'verified']);

Route::post('/refunds/approveRefund/{refund}', [RefundController::class, 'approveRefund'])
    ->name('refunds.approveRefund')
    ->middleware(['auth', 'verified']);

Route::post('/refunds/denyRefund/{refund}', [RefundController::class, 'denyRefund'])
    ->name('refunds.denyRefund')
    ->middleware(['auth', 'verified']);

Route::resource('events', EventController::class)
    ->only(['index', 'store', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
    ->middleware(['auth', 'verified']);

Route::resource('registrations', RegistrationController::class)
    ->only(['index', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::resource('payments', PaymentController::class)
    ->only(['index',])
    ->middleware(['auth', 'verified']);

Route::resource('refunds', RefundController::class)
    ->only(['index', 'show', 'destroy'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
