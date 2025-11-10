<?php

use App\Http\Controllers\CompteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/api/documentation', function () {
    return view('vendor.l5-swagger.index');
});

/**
 * @OA\PathItem(
 *     path="/api/v1/comptes"
 * )
 */

// API Version 1
Route::prefix('v1')->group(function () {

    Route::get('/comptes', [CompteController::class, 'index']);

    
    Route::post('/comptes', [CompteController::class, 'store']);
});

