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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->unsignedBigInteger('employee_id')->comment('ID nhân viên');
            $table->date('work_date')->comment('Ngày làm việc');
            $table->boolean('is_overtime')->default(false)->comment('Có làm thêm giờ không');
            $table->unsignedBigInteger('working_shift_id')->nullable()->comment('ID ca làm thêm (nếu có)');
            $table->unsignedBigInteger('attendance_id')->nullable()->comment('ID bảng công đã chốt');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('working_shift_id')->references('id')->on('working_shifts')->onDelete('set null');
            $table->foreign('attendance_id')->references('id')->on('attendance')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
