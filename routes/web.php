<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralCatalogController;

Route::get('/', function () {
    return view('page.system..user.index');
});
Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
    });
Route::prefix('general_catalog')
    ->name('general_catalog.')
    ->group(function () {
        Route::get('/department-position', [GeneralCatalogController::class, 'showIndexDepartmentPosition'])->name('showIndexDepartmentPosition');
        Route::get('/department/create', [GeneralCatalogController::class, 'showCreateDepartment'])->name('showCreateDepartment');
        Route::get('/department/update', [GeneralCatalogController::class, 'showUpdateDepartment'])->name('showUpdateDepartment');
        Route::get('/position/create', [GeneralCatalogController::class, 'showCreatePosition'])->name('showCreatePosition');
        Route::get('/position/update', [GeneralCatalogController::class, 'showUpdatePosition'])->name('showUpdatePosition');
    });
