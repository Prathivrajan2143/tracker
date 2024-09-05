<?php

use App\Http\Controllers\Organization\OrganizationController;
use App\Http\Controllers\Organization\OrganizationLoginController;
use Illuminate\Support\Facades\Route;





Route::prefix('organization')->group(function () {
    // Invitation routes
    Route::post('/invite', [OrganizationController::class, 'invite']);
    Route::get('/invite/validate', [OrganizationController::class, 'validateInvite'])->name('invite.handle');

    // Data route
    Route::get('/get-organizations', [OrganizationController::class, 'getOrganizations']);

    // Temporary login routes
    Route::post('/temporary-login', [OrganizationController::class, 'temporaryLogin']);
    Route::post('/verify-otp', [OrganizationController::class, 'verifyTemporaryLoginOtp']);
    Route::post('/resend-otp', [OrganizationController::class, 'resendTemporaryLoginOtp']);

    // Login setup route
    Route::post('/login-setup', [OrganizationLoginController::class, 'loginSetup']);

    // Custom login page route
    Route::post('/custom-login', [OrganizationLoginController::class, 'storeCustomLoginPage']);
});