<?php

use App\Http\Controllers\CheckingAccountController;
use App\Http\Controllers\InvestmentAccountController;
use App\Http\Controllers\ProfileController;
use App\Models\CheckingAccount;
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
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('index');
})->name('index');

Route::get('/dashboard', function () {
    $checkingAccounts = [];
    $latestTransactions = [];
    if (auth()->check()) {
        $checkingAccounts = auth()->user()->userCheckingAccounts()->latest()->get();
        $latestTransactions = auth()->user()->getUserTransactions()->latest()->limit(3)->get();
    }

    return view('dashboard', ['checkingAccounts' => $checkingAccounts, 'latestTransactions' => $latestTransactions]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::post('/create-new-checking-account', [CheckingAccountController::class, 'create'])->middleware(['auth', 'verified']);
Route::post('/send-money', [CheckingAccountController::class, 'sendMoney'])->middleware(['auth', 'verified']);
Route::post('/add-money', [CheckingAccountController::class, 'addMoney'])->middleware(['auth', 'verified']);

Route::get('/dashboard/invest', function () {
    $investmentAccount = [];
    if (auth()->check()) {
        $investmentAccount = auth()->user()->userInvestmentAccount()->first();
    }
    $cryptoInvestments = [];
    if ($investmentAccount) {
        $cryptoInvestments = json_decode($investmentAccount->investments)->crypto;
    }

    $usdCheckingAccounts = [];
    if (auth()->check() && auth()->user()->userCheckingAccounts()->where('currency_iso', '=', 'USD')->first()) {
        $usdCheckingAccounts = auth()->user()->userCheckingAccounts()->where('currency_iso', '=', 'USD')->latest()->get();
    }

    return view('dashboard/invest', [
        'investmentAccount' => $investmentAccount,
        'cryptoInvestments' => $cryptoInvestments,
        'usdDebitAccounts' => $usdCheckingAccounts]);
})->middleware(['auth', 'verified'])->name('invest');

Route::post('/dashboard/invest', [InvestmentAccountController::class, 'create'])->middleware(['auth', 'verified']);

Route::post('/buy-crypto', [InvestmentAccountController::class, 'buyCrypto'])->middleware(['auth', 'verified']);
Route::post('/sell-crypto', [InvestmentAccountController::class, 'sellCrypto'])->middleware(['auth', 'verified']);


Route::get('/dashboard/transactions', function () {
    $transactions = [];
    if (auth()->check()) {
        $transactions = auth()->user()->getUserTransactions()->get();
    }
    return view('dashboard/transactions', ['transactions' => $transactions]);
})->middleware(['auth', 'verified'])->name('transactions');
