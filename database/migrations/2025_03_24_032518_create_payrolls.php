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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->comment('ID nhân viên');
            $table->integer('month')->comment('Tháng');
            $table->integer('year')->comment('Năm');
            $table->integer('salary_v1')->comment('Lương cơ bản');
            $table->integer('total_allowance')->comment('Tổng phụ cấp');
            $table->integer('total_deduction')->comment('Tổng khoản trích');
            $table->integer('total_bonus')->default(0)->comment('Tổng khoản thưởng');
            $table->integer('working_shift_amount')->default(0)->comment('Tổng tiền ca làm thêm');
            $table->integer('tax_amount')->default(0)->comment('Tổng thuế thu nhập cá nhân');
            $table->integer('net_salary_before_tax')->default(0)->comment('Lương thực lĩnh trước thuế');
            $table->integer('net_salary_after_tax')->default(0)->comment('Lương thực lĩnh sau thuế');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
