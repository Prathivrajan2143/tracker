<?php

use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::POST('/organization/invite', [OrganizationController::class, 'organizationInvite'])->name('org.invite');

Route::GET('/organization/data', [OrganizationController::class, 'organizationData'])->name('organization.data');
 
Route::GET('/organization/invite/validate', [OrganizationController::class, 'urlValidate'])->name('invite.handle');

Route::POST('/organization/temporary/login', [OrganizationController::class, 'tempLoginSendOtp'])->name('temporary.login');

Route::POST('/organization/verify/otp', [OrganizationController::class, 'verifyOtp'])->name('verify.otp');

Route::POST('/organization/resend/otp', [OrganizationController::class, 'resendOtp'])->name('resend.otp');

Route::POST('/organization/login/setup', [OrganizationLoginController::class, 'organizationLoginSetup'])->name('org.login.setup');

Route::post('/organization/customized/login', [OrganizationLoginController::class, 'storeCustomizeLoginPage'])->name('store.customize.page');