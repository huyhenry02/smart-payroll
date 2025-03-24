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
            $table->integer('base_salary')->comment('Lương cơ bản');
            $table->integer('total_allowance')->comment('Tổng phụ cấp');
            $table->integer('total_deduction')->comment('Tổng khoản trích');
            $table->integer('net_salary')->comment('Lương thực lĩnh');
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
