<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Create doc_types table
        Schema::create('doc_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('status')->default(value: 1);
            $table->boolean('is_show')->default(value: 0);
            $table->softDeletes();
            $table->timestamps();
        });

        // Step 2: Seed doc_types
        DB::table('doc_types')->insert([
            ['id' => 1, 'name' => 'Income Tax Return', 'slug' => 'itr', 'status' => 1, 'is_show' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Computations', 'slug' => 'computation', 'status' => 1, 'is_show' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Balance Sheet', 'slug' => 'balance_sheet', 'status' => 1, 'is_show' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Audit Files', 'slug' => 'audit', 'status' => 1, 'is_show' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'PL A/c', 'slug' => 'pl', 'status' => 1, 'is_show' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Others', 'slug' => 'other', 'status' => 1, 'is_show' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Step 3: Rename the old doc_type to doc_type_slug temporarily
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->renameColumn('doc_type', 'doc_type_slug');
        });

        // Step 4: Add new integer doc_type column
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('doc_type')->nullable()->after('doc_type_slug');
        });

        // Step 5: Update doc_type (int) based on slug
        $slugToIdMap = [
            'itr' => 1,
            'computation' => 2,
            'balance_sheet' => 3,
            'audit' => 4,
            'pl' => 5,
            'other' => 6,
        ];

        foreach ($slugToIdMap as $slug => $id) {
            DB::table('customer_documents')
                ->where('doc_type_slug', $slug)
                ->update(['doc_type' => $id]);
        }

        // Step 6: Make doc_type not nullable and drop doc_type_slug
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('doc_type')->nullable(false)->change();
            $table->dropColumn('doc_type_slug');
        });

        // Optional: add foreign key constraint
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->foreign('doc_type')->references('id')->on('doc_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Remove foreign key
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->dropForeign(['doc_type']);
        });

        // Step 2: Add back doc_type_slug (string)
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->string('doc_type_slug')->nullable()->after('doc_type');
        });

        // Step 3: Re-populate doc_type_slug from doc_type (int)
        $idToSlugMap = [
            1 => 'itr',
            2 => 'computation',
            3 => 'balance_sheet',
            4 => 'audit',
            5 => 'pl',
            6 => 'other',
        ];

        foreach ($idToSlugMap as $id => $slug) {
            DB::table('customer_documents')
                ->where('doc_type', $id)
                ->update(['doc_type_slug' => $slug]);
        }

        // Step 4: Drop integer doc_type column and rename slug back
        Schema::table('customer_documents', function (Blueprint $table) {
            $table->dropColumn('doc_type');
        });

        Schema::table('customer_documents', function (Blueprint $table) {
            $table->renameColumn('doc_type_slug', 'doc_type');
        });

        // Step 5: Drop doc_types table
        Schema::dropIfExists('doc_types');
    }
};
