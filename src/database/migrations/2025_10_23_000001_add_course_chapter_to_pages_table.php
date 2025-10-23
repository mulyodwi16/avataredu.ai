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
        Schema::table('pages', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->constrained('course_chapters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeignIdFor('course_id');
            $table->dropForeignIdFor('chapter_id', 'course_chapters');
        });
    }
};
