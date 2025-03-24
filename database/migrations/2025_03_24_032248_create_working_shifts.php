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
        Schema::create('working_shifts', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->enum('type', ['morning', 'afternoon', 'night', 'weekend', 'holiday'])->comment('Loại ca làm việc');
            $table->integer('hourly_rate')->comment('Tiền làm thêm / giờ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_shifts');
    }
};
