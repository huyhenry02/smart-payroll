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
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->string('employee_code', 50)->unique()->comment('Mã nhân viên');
            $table->string('full_name', 100)->comment('Họ và tên');
            $table->date('dob')->comment('Ngày sinh');
            $table->enum('gender', ['male', 'female', 'other'])->comment('Giới tính');
            $table->text('address')->nullable()->comment('Địa chỉ');
            $table->unsignedBigInteger('department_id')->comment('Phòng ban');
            $table->unsignedBigInteger('position_id')->comment('Chức vụ');
            $table->integer('base_salary')->comment('Lương cơ bản');
            $table->float('salary_factor')->comment('Hệ số lương');
            $table->integer('seniority')->comment('Thâm niên (năm)');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
