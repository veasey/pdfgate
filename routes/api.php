<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/tokens', [AuthController::class, 'createToken']);
    Route::delete('/tokens/{token_id}', [AuthController::class, 'revokeToken']);

    Route::get('/subscription', [SubscriptionController::class, 'status']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);

    Route::post('/pdf', [PdfController::class, 'generate'])->name('api.pdf.generate');
    Route::get('/pdf/{pdfJob}', [PdfController::class, 'show'])->name('api.pdf.show');
    Route::get('/pdf/{pdfJob}/download', [PdfController::class, 'download'])->name('api.pdf.download');
});
