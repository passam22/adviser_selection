<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdviserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PublicController;

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/sample/{try}', [LoginController::class, 'sample']);
Route::post('/check_login', [LoginController::class, 'check_login']);


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard']);

    //ADVISERS
    Route::get('/application_list', [AdviserController::class, 'application_list']);
    Route::get('/approve_group/{id}', [AdviserController::class, 'approve_group']);
    Route::get('/selected_groups', [AdviserController::class, 'selected_groups']);
    Route::post('/reject_group/{id}', [AdviserController::class, 'reject_group']);

    //GROUPS
    Route::get('/select_adviser', [GroupController::class, 'select_adviser']);
    Route::get('/request_adviser/{id}', [GroupController::class, 'request_adviser']);
    Route::get('/requests_sent', [GroupController::class, 'requests_sent']);
    Route::get('/cancel_request/{id}', [GroupController::class, 'cancel_request']);

    //ALL
    Route::get('/list_of_groups', [PublicController::class, 'list_of_groups']);
});