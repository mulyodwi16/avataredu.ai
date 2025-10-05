<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('course_chapters')) {
            Schema::create('course_chapters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('course_lessons')) {
            Schema::create('course_lessons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chapter_id')->constrained('course_chapters', 'id')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('video_url')->nullable();
                $table->string('duration')->nullable();
                $table->text('content')->nullable();
                $table->json('attachments')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lesson_progress')) {
            Schema::create('lesson_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('lesson_id')->constrained('course_lessons')->onDelete('cascade');
                $table->boolean('is_completed')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('last_accessed_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'lesson_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_chapters');
    }
};