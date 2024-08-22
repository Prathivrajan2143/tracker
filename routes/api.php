<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::POST('/organization-invite', [OrganizationController::class, 'organizationInvite'])->name('org.invite');

// Route::POST('/invite/{domain}', function () {

//     return 'returned in api';
    
// })->name('apiroute.handle');

Route::GET('/invite', function () {

        return 'Hello';
        
    })->middleware('signed')->name('apiroute');