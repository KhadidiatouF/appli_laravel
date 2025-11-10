<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes Swagger
Route::get('/docs', function () {
    $documentation = 'default';
    $urlToDocs = route('l5-swagger.default.api');
    $useAbsolutePath = config('l5-swagger.defaults.paths.use_absolute_path', true);
    return view('vendor.l5-swagger.index', compact('documentation', 'urlToDocs', 'useAbsolutePath'));
})->name('l5-swagger.default.docs');

Route::get('/docs/api-docs.json', function () {
    $filePath = storage_path('api-docs/api-docs.json');
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/json']);
    }
    return response()->json(['error' => 'Documentation not generated'], 404);
})->name('l5-swagger.default.api');

