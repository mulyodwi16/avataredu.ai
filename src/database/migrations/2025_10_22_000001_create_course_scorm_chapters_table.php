<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('course_scorm_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title'); // Chapter name (e.g., "Chapter 1: Introduction")
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Order of chapters
            $table->string('scorm_version')->default('1.2'); // SCORM 1.2 or 2004
            $table->json('scorm_manifest')->nullable(); // Parsed manifest data
            $table->string('scorm_entry_point'); // Main file to load (e.g., index.html)
            $table->string('scorm_package_path'); // Path to extracted SCORM package
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_scorm_chapters');
    }
};
