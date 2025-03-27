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
        Route::get('/department', [GeneralCatalogController::class, 'showIndexDepartment'])->name('showIndexDepartment');
        Route::get('/position', [GeneralCatalogController::class, 'showIndexPosition'])->name('showIndexPosition');

        Route::post('/department', [GeneralCatalogController::class, 'postDepartment'])->name('postDepartment');
        Route::post('/position', [GeneralCatalogController::class, 'postPosition'])->name('postPosition');
        Route::get('/department/delete/{department}', [GeneralCatalogController::class, 'deleteDepartment'])->name('deleteDepartment');
        Route::get('/position/delete/{position}', [GeneralCatalogController::class, 'deletePosition'])->name('deletePosition');
        Route::post('/department/update/{department}', [GeneralCatalogController::class, 'putDepartment'])->name('putDepartment');
        Route::post('/position/update/{position}', [GeneralCatalogController::class, 'putPosition'])->name('putPosition');
    });
