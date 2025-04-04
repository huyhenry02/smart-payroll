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
            $table->integer('month')->comment('Tháng chấm công');
            $table->integer('year')->comment('Năm chấm công');
            $table->integer('working_days')->default(0)->comment('Tổng số ngày đi làm');
            $table->integer('leave_days')->default(0)->comment('Tổng số ngày nghỉ phép');
            $table->integer('overtime_hours')->default(0)->comment('Tổng số ca làm thêm');
            $table->boolean('is_finalized')->default(true)->comment('Đã chốt công hay chưa');
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year'], 'attendance_unique');
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
