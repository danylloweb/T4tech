<?php

use App\Http\Controllers\{
    AuthController,
    TeamsController,
    PlayersController,
    GamesController
};
use Illuminate\Support\Facades\Route;

Route::get('health-check/alive',function () {
    return response()->json(['message' => 'ok'], 200);
})->name('health.check.alive');

Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');

    Route::apiResource('teams', TeamsController::class);
    Route::apiResource('players', PlayersController::class);
    Route::apiResource('games', GamesController::class);
});

Route::any('{any}', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->where('any', '.*');

Route::fallback(function () {
    return response()->json(['message' => 'Unauthorized'], 401);
});


