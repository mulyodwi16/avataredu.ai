<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->integer('duration_hours')->default(0);
            $table->json('curriculum')->nullable(); // JSON struktur kurikulum
            $table->integer('total_chapters')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->boolean('is_published')->default(false);
            $table->integer('enrolled_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};