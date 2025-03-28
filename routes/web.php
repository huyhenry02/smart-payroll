<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralCatalogController;

Route::get('/', function () {
    return redirect()->route('system.showIndexUser');
});
Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
        Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });
Route::prefix('general_catalog')
    ->name('general_catalog.')
    ->group(function () {
        Route::get('/department', [GeneralCatalogController::class, 'showIndexDepartment'])->name('showIndexDepartment');
        Route::get('/position', [GeneralCatalogController::class, 'showIndexPosition'])->name('showIndexPosition');
        Route::get('/employee', [GeneralCatalogController::class, 'showIndexEmployee'])->name('showIndexEmployee');
        Route::get('/update-employee/{user}', [GeneralCatalogController::class, 'showUpdateEmployee'])->name('showUpdateEmployee');

        Route::post('/department', [GeneralCatalogController::class, 'postDepartment'])->name('postDepartment');
        Route::post('/position', [GeneralCatalogController::class, 'postPosition'])->name('postPosition');
        Route::get('/department/delete/{department}', [GeneralCatalogController::class, 'deleteDepartment'])->name('deleteDepartment');
        Route::get('/position/delete/{position}', [GeneralCatalogController::class, 'deletePosition'])->name('deletePosition');
        Route::post('/department/update/{department}', [GeneralCatalogController::class, 'putDepartment'])->name('putDepartment');
        Route::post('/position/update/{position}', [GeneralCatalogController::class, 'putPosition'])->name('putPosition');
        Route::post('/employee/update/{employee}', [GeneralCatalogController::class, 'putEmployee'])->name('putEmployee');
    });
Route::prefix('system')
    ->name('system.')
    ->group(function () {
        Route::get('/user', [SystemController::class, 'showIndexUser'])->name('showIndexUser');

        Route::post('/user', [SystemController::class, 'postUser'])->name('postUser');
    });
