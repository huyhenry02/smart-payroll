<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AllowanceDeductionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
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
    ->middleware('auth')
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
        Route::get('/search-employee', [GeneralCatalogController::class, 'searchEmployee'])->name('searchEmployee');

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

        // Bonus
        Route::get('/bonus', [GeneralCatalogController::class, 'showIndexBonus'])->name('showIndexBonus');
        Route::post('/bonus/update/{bonus}', [GeneralCatalogController::class, 'putBonus'])->name('putBonus');
        Route::get('/deleteBonus/{bonus}', [GeneralCatalogController::class, 'deleteBonus'])->name('deleteBonus');
        Route::post('/bonus', [GeneralCatalogController::class, 'postBonus'])->name('postBonus');
    });
Route::prefix('system')
    ->middleware('auth')
    ->name('system.')
    ->group(function () {
        Route::get('/user', [SystemController::class, 'showIndexUser'])->name('showIndexUser');

        Route::post('/user', [SystemController::class, 'postUser'])->name('postUser');
    });
Route::prefix('allowance_deduction')
    ->middleware('auth')
    ->name('allowance_deduction.')
    ->group(function () {
        Route::get('/allowance', [AllowanceDeductionController::class, 'showIndexAllowance'])->name('showIndexAllowance');
        Route::post('/allowance/update', [AllowanceDeductionController::class, 'putAllowance'])->name('putAllowance');
        Route::get('/allowance/preview', [AllowanceDeductionController::class, 'previewAllowancePdf'])->name('previewAllowancePdf');

        Route::get('/deduction', [AllowanceDeductionController::class, 'showIndexDeduction'])->name('showIndexDeduction');
        Route::post('/deduction/update', [AllowanceDeductionController::class, 'putDeduction'])->name('putDeduction');
        Route::get('/deduction/preview', [AllowanceDeductionController::class, 'previewDeductionPdf'])->name('previewDeductionPdf');
    });
Route::prefix('attendance')
    ->middleware('auth')
    ->name('attendance.')
    ->group(function () {
        Route::get('/detail', [AttendanceController::class, 'showDetailAttendance'])->name('showDetailAttendance');
        Route::get('/detail-attendance/load', [AttendanceController::class, 'loadDetailTable'])->name('detail-attendance.load');
        Route::get('/summary/{month}', [AttendanceController::class, 'showSummary'])->name('showSummary');
        Route::get('/overtime/{month}', [AttendanceController::class, 'showOvertime'])->name('showOvertime');
        Route::get('/personal/{month}', [AttendanceController::class, 'showPersonal'])->name('showPersonal');

        Route::post('/detail-attendance/aupdate', [AttendanceController::class, 'updateDetail'])->name('detail-attendance.update');
        Route::post('/post-close', [AttendanceController::class, 'postCloseAttendance'])->name('postCloseAttendance');
        Route::post('/overtime/post', [AttendanceController::class, 'postOvertime'])->name('postOvertime');
        Route::post('/overtime/update/{attendanceDetail}', [AttendanceController::class, 'putOvertime'])->name('putOvertime');
        Route::get('/overtime/delete/{attendanceDetail}', [AttendanceController::class, 'deleteOvertime'])->name('deleteOvertime');

    });

Route::prefix('accounting')
    ->middleware('auth')
    ->name('accounting.')
    ->group(function () {
        Route::get('/', [AccountingController::class, 'showIndex'])->name('showIndex');
        Route::get('/load', [AccountingController::class, 'loadIndex'])->name('loadIndex');
        Route::get('/tax', [AccountingController::class, 'showIndexTax'])->name('showIndexTax');
        Route::get('/load-tax', [AccountingController::class, 'loadIndexTax'])->name('loadIndexTax');
        Route::get('/bonus', [AccountingController::class, 'showEmployeeBonus'])->name('showEmployeeBonus');
        Route::get('/bonus/load', [AccountingController::class, 'loadEmployeeBonusTable'])->name('loadEmployeeBonusTable');
        Route::get('/preview-tax-pdf/{month}', [AccountingController::class, 'previewTaxPdf'])->name('previewTaxPdf');
        Route::get('/preview-payment-pdf/{month}', [AccountingController::class, 'previewPaymentPdf'])->name('previewPaymentPdf');
        Route::get('/unit-price', [AccountingController::class, 'getUnitPrice'])->name('getUnitPrice');
        Route::get('/payment', [AccountingController::class, 'showPayment'])->name('showPayment');
        Route::get('/payment/load', [AccountingController::class, 'loadPayment'])->name('loadPayment');

        Route::post('/post', [AccountingController::class, 'postPayrollTable'])->name('postPayrollTable');
        Route::post('/bonus/update', [AccountingController::class, 'updateEmployeeBonus'])->name('updateEmployeeBonus');
    });

Route::prefix('journal')
    ->middleware('auth')
    ->name('journal.')
    ->group(function () {
        Route::get('/', [ReportController::class, 'showJournal'])->name('showJournal');
        Route::get('/load', [ReportController::class, 'loadJournal'])->name('loadJournal');

        Route::post('/accounting/journal/save', [ReportController::class, 'saveJournal'])->name('saveJournal');
    });
