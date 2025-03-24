<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->unsignedBigInteger('employee_id')->comment('ID nhân viên');
            $table->integer('month')->comment('Tháng');
            $table->integer('year')->comment('Năm');
            $table->integer('working_days')->comment('Số ngày làm việc');
            $table->integer('leave_days')->comment('Số ngày nghỉ phép');
            $table->integer('overtime_hours')->comment('Tổng số giờ làm thêm');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
