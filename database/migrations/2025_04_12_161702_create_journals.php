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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->integer('month')->comment('Tháng');
            $table->integer('year')->comment('Năm');
            $table->string('description',255)->comment('Diễn giải');
            $table->date('date_journaling')->comment('Ngày hạch toán');
            $table->string('content',255)->comment('Nội dung');
            $table->string('debt_account',255)->comment('Tài khoản nợ');
            $table->string('has_account',255)->comment('Tài khoản có');
            $table->integer('amount')->comment('Số tiền');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
