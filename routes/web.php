<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\Api\GameController as ApiGameController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/home', [GameController::class, 'home'])->name('home');
    
    // Games
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/{category}', [GameController::class, 'byCategory'])->name('games.category');
    Route::get('/play/{gameId}', [GameController::class, 'play'])->name('game.play');
    
    // Lottery/Betting
    Route::get('/lottery', [BetController::class, 'index'])->name('lottery.index');
    Route::get('/lottery/wingo', [BetController::class, 'wingo'])->name('lottery.wingo');
    Route::post('/lottery/place-bet', [BetController::class, 'placeBet'])->name('bet.place');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/deposit', [WalletController::class, 'processDeposit'])->name('wallet.deposit.process');
    Route::get('/wallet/withdrawal', [WalletController::class, 'withdrawal'])->name('wallet.withdrawal');
    Route::post('/wallet/withdrawal', [WalletController::class, 'processWithdrawal'])->name('wallet.withdrawal.process');
    
    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Referral
    Route::get('/referral', [GameController::class, 'referral'])->name('referral');
    Route::get('/referral/team', [GameController::class, 'referralTeam'])->name('referral.team');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    // Games API
    Route::get('/games', [ApiGameController::class, 'getAllGames'])->name('api.games');
    Route::get('/games/category/{category}', [ApiGameController::class, 'getGamesByCategory']);
    Route::get('/games/{gameId}', [ApiGameController::class, 'getGame']);
    Route::get('/games/featured', [ApiGameController::class, 'featured'])->name('api.games.featured');
    
    // Protected API routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/games/{gameId}/launch', [ApiGameController::class, 'launchGame']);
        Route::post('/bets', [BetController::class, 'storeBet']);
        Route::get('/bets', [BetController::class, 'getUserBets']);
        Route::get('/wallet/balance', [WalletController::class, 'getBalance']);
    });
});
