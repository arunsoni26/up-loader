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
        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gst_year_id')->constrained()->cascadeOnDelete();
            $table->enum('doc_type', ['itr','computation','balance_sheet','audit','pl']);
            $table->string('description')->nullable();
            $table->string('file_path'); // storage path (public disk)
            $table->foreignId(column: 'uploaded_by')->default(1)->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['customer_id','gst_year_id','doc_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_documents');
    }
};
