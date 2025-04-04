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
        Schema::create('allowances', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->string('name', 100)->comment('Tên khoản phụ cấp / trợ cấp');
            $table->enum('type', ['position', 'region', 'hazard', 'responsibility'])->comment('Loại phụ cấp');
            $table->float('rate')->comment('Tỷ lệ phụ cấp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowances');
    }
};
