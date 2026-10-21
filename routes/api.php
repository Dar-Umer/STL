<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmerController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PlotController;
use App\Http\Controllers\Api\ReferenceController;
use App\Http\Controllers\Api\SampleCollectionController;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\TargetController;
use App\Http\Controllers\Api\FrontPageController;
use App\Http\Controllers\SampleDocumentController;
use Illuminate\Support\Facades\Route;

// ---- Public Auth ----
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// ---- Front Page (public - needed for maintenance mode check before login) ----
Route::get('/front-page', [FrontPageController::class, 'index']);

// ---- Authenticated ----
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    Route::post('/user/avatar', [AuthController::class, 'updateAvatar']);

    // Reference data
    Route::get('/soil-types', [ReferenceController::class, 'soilTypes']);
    Route::get('/crops', [ReferenceController::class, 'crops']);
    Route::get('/soil-colors', [ReferenceController::class, 'soilColors']);

    // ---- Sample Requests ----
    Route::get('/notifications/pending-requests', [NotificationController::class, 'pendingRequests']);
    Route::get('/notifications', [NotificationController::class, 'listAll']);
    Route::get('/notifications/unread', [NotificationController::class, 'unreadNotifications']);
    Route::post('/notifications/register-device', [NotificationController::class, 'registerDevice']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markRead']);
    Route::get('/requests/{id}', [NotificationController::class, 'requestDetail']);
    Route::post('/requests/{id}/accept', [NotificationController::class, 'acceptRequest']);
    Route::post('/requests/{id}/prepare-farmer', [NotificationController::class, 'prepareFarmer']);

    // ---- Farmers ----
    Route::middleware('permission:register-farmers')->group(function () {
        Route::get('/farmers', [FarmerController::class, 'index']);
        Route::get('/farmers/search', [FarmerController::class, 'search']);
        Route::get('/farmers/{id}', [FarmerController::class, 'show']);
        Route::get('/farmers/{id}/plots', [FarmerController::class, 'plots']);
        Route::post('/farmers', [FarmerController::class, 'store']);
    });

    // ---- Plots ----
    Route::post('/plots', [PlotController::class, 'store'])->middleware('permission:manage-plots');

    // ---- Locations ----
    Route::get('/locations/states', [LocationController::class, 'states']);
    Route::get('/locations/{parentId}/children', [LocationController::class, 'children']);
    Route::get('/locations/{id}/ancestors', [LocationController::class, 'ancestors']);

    // ---- My Samples ----
    Route::get('/samples', [SampleController::class, 'index']);
    Route::get('/samples/{sample}', [SampleController::class, 'show']);
    Route::get('/samples/{sample}/acknowledgement/pdf', [SampleDocumentController::class, 'acknowledgementPdf']);
    Route::get('/samples/{sample}/report/pdf', [SampleDocumentController::class, 'downloadReport']);

    // ---- Sample Collection ----
    Route::middleware('permission:collect-samples')->group(function () {
        Route::get('/qr-codes/available', [SampleCollectionController::class, 'availableQrCodes']);
        Route::post('/qr-codes/validate', [SampleCollectionController::class, 'validateQr']);
        Route::post('/samples/collect', [SampleCollectionController::class, 'collect']);
    });

    // ---- Targets ----
    Route::get('/targets', [TargetController::class, 'index']);

    // ---- Covering Letter ----
    Route::get('/covering-letter/data', [\App\Http\Controllers\Api\CoveringLetterController::class, 'getData']);
    Route::post('/covering-letter/pdf', [\App\Http\Controllers\Api\CoveringLetterController::class, 'generatePdf']);
});
