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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('gst_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('pan')->nullable();
            $table->string('pan_doc')->nullable();
            $table->enum('client_type_status', ['gst', 'itr', 'telly'])->nullable();
            $table->string('code')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('customer_groups')->nullOnDelete();
            $table->date('dob')->nullable();
            $table->string('gst')->nullable();
            $table->string('gst_doc')->nullable();
            $table->string('aadhar')->nullable();
            $table->string('aadhar_doc')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(1); // 1 = active
            $table->boolean('hide_dashboard')->default(1); // 1 = unhide
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
