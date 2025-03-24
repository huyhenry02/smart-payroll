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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uploaded_by')->nullable()->comment('ID người tải lên');
            $table->enum('file_type', ['payment_voucher', 'journal_voucher', 'payroll_sheet', 'summary_report'])->comment('Loại chứng từ');
            $table->enum('book_type', ['general_ledger', 'cash_book', 'general_journal', 'sub_ledger'])->comment('Loại sổ');
            $table->string('file_name', 255)->comment('Tên file');
            $table->string('file_path', 255)->comment('Đường dẫn lưu file');
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
