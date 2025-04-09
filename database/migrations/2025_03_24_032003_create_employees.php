<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->unsignedBigInteger('user_id')->comment('Id tài khoản');
            $table->string('avatar', 255)->nullable()->comment('Avt nhân viên');
            $table->string('employee_code', 50)->unique()->comment('Mã nhân viên');
            $table->string('full_name', 100)->comment('Họ và tên');
            $table->date('dob')->nullable()->comment('Ngày sinh');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->comment('Giới tính');
            $table->string('identity_number', 20)->nullable()->unique()->comment('CMND/CCCD');
            $table->date('identity_issued_date')->nullable()->comment('Ngày cấp CMND/CCCD');
            $table->string('identity_issued_place')->nullable()->comment('Nơi cấp CMND/CCCD');
            $table->text('address')->nullable()->comment('Địa chỉ thường trú');
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            $table->unsignedBigInteger('department_id')->nullable()->comment('Phòng ban');
            $table->unsignedBigInteger('position_id')->nullable()->comment('Chức vụ');
            $table->date('start_date')->nullable()->comment('Ngày vào làm');
            $table->enum('employment_status', ['working', 'resigned'])->default('working')->comment('Tình trạng làm việc');
            $table->string('contract_type')->nullable()->comment('Loại hợp đồng');
            $table->float('salary_factor')->nullable()->comment('Hệ số lương');
            $table->integer('seniority')->default(0)->comment('Thâm niên (năm)');
            $table->string('tax_code')->nullable()->comment('Mã số thuế cá nhân');
            $table->string('bank_account')->nullable()->comment('Số tài khoản ngân hàng');
            $table->string('bank_name')->nullable()->comment('Tên ngân hàng');
            $table->string('education_level')->nullable()->comment('Trình độ học vấn');
            $table->string('specialization')->nullable()->comment('Chuyên môn');
            $table->integer('number_of_dependent')->nullable()->comment('Số người phụ thuộc');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
