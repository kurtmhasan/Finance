<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\InvestController;


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
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('/invest', [InvestController::class, 'index'])->name('invest.index');
        Route::get('/transfer', [WalletController::class, 'transfer'])->name('money.transfer');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-data', [WalletController::class, 'showDashboardData']);
});

Route::post('/sendMoney', [TransferController::class, 'sendMoney']);

Route::middleware('auth')->group(function () {
    Route::post('/getStockName', [InvestController::class, 'getStockName']);
    Route::post('/buyStock', [InvestController::class, 'buyStock']);
});

require __DIR__.'/auth.php';
