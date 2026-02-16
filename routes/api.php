<?php

use App\Http\Controllers\{ServiceConfigurationsController};
use Illuminate\Support\Facades\Route;
Route::get('health-check/alive',function () {
    return response()->json(['message' => 'ok'], 200);
})->name('health.check.alive');


Route::any('{any}', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->where('any', '.*');

Route::fallback(function () {
    return response()->json(['message' => 'Unauthorized'], 401);
});
