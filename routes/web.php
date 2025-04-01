<?php

use App\Http\Controllers\AllowanceDeductionController;
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
        // Department
        Route::get('/department', [GeneralCatalogController::class, 'showIndexDepartment'])->name('showIndexDepartment');
        Route::post('/department', [GeneralCatalogController::class, 'postDepartment'])->name('postDepartment');
        Route::get('/department/delete/{department}', [GeneralCatalogController::class, 'deleteDepartment'])->name('deleteDepartment');
        Route::post('/department/update/{department}', [GeneralCatalogController::class, 'putDepartment'])->name('putDepartment');

        // Position
        Route::get('/position', [GeneralCatalogController::class, 'showIndexPosition'])->name('showIndexPosition');
        Route::post('/position', [GeneralCatalogController::class, 'postPosition'])->name('postPosition');
        Route::get('/position/delete/{position}', [GeneralCatalogController::class, 'deletePosition'])->name('deletePosition');
        Route::post('/position/update/{position}', [GeneralCatalogController::class, 'putPosition'])->name('putPosition');

        // Employee
        Route::get('/employee', [GeneralCatalogController::class, 'showIndexEmployee'])->name('showIndexEmployee');
        Route::get('/update-employee/{user}', [GeneralCatalogController::class, 'showUpdateEmployee'])->name('showUpdateEmployee');
        Route::post('/employee/update/{employee}', [GeneralCatalogController::class, 'putEmployee'])->name('putEmployee');

        // Working shift
        Route::get('/working-shift', [GeneralCatalogController::class, 'showIndexWorkingShift'])->name('showIndexWorkingShift');
        Route::post('/working-shift/update/{workingShift}', [GeneralCatalogController::class, 'putWorkingShift'])->name('putWorkingShift');
        Route::get('/deleteWorkingShift/{workingShift}', [GeneralCatalogController::class, 'deleteWorkingShift'])->name('deleteWorkingShift');

        // Deduction
        Route::get('/deduction', [GeneralCatalogController::class, 'showIndexDeduction'])->name('showIndexDeduction');
        Route::post('/deduction/update/{deduction}', [GeneralCatalogController::class, 'putDeduction'])->name('putDeduction');
        Route::get('/deleteDeduction/{deduction}', [GeneralCatalogController::class, 'deleteDeduction'])->name('deleteDeduction');
        Route::post('/deduction', [GeneralCatalogController::class, 'postDeduction'])->name('postDeduction');

        // Allowance
        Route::get('/allowance', [GeneralCatalogController::class, 'showIndexAllowance'])->name('showIndexAllowance');
        Route::post('/allowance/update/{allowance}', [GeneralCatalogController::class, 'putAllowance'])->name('putAllowance');
        Route::get('/deleteAllowance/{allowance}', [GeneralCatalogController::class, 'deleteAllowance'])->name('deleteAllowance');
        Route::post('/allowance', [GeneralCatalogController::class, 'postAllowance'])->name('postAllowance');
    });
Route::prefix('system')
    ->name('system.')
    ->group(function () {
        Route::get('/user', [SystemController::class, 'showIndexUser'])->name('showIndexUser');

        Route::post('/user', [SystemController::class, 'postUser'])->name('postUser');
    });
Route::prefix('allowance_deduction')
    ->name('allowance_deduction.')
    ->group(function () {
        Route::get('/allowance', [AllowanceDeductionController::class, 'showIndexDeduction'])->name('showIndexDeduction');
        Route::post('/allowance/update', [AllowanceDeductionController::class, 'putAllowance'])->name('putAllowance');
        Route::get('/allowance/preview', [AllowanceDeductionController::class, 'previewAllowancePdf'])->name('previewAllowancePdf');

        Route::get('/deduction', [AllowanceDeductionController::class, 'showIndexAllowance'])->name('showIndexAllowance');
        Route::post('/deduction/update', [AllowanceDeductionController::class, 'putDeduction'])->name('putDeduction');
        Route::get('/deduction/preview', [AllowanceDeductionController::class, 'previewDeductionPdf'])->name('previewDeductionPdf');

    });
