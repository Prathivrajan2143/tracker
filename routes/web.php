<?php

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () { return view('welcome'); });

// Route::get('/invite/{domain}', [InviteController::class, 'handleInvite'])
//     // ->middleware('signed')
//     ->name('invite.handle');