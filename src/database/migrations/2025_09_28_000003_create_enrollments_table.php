<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->json('completed_lessons')->nullable(); // Array of completed lesson IDs
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Satu user hanya bisa enroll sekali di course yang sama
            $table->unique(['user_id', 'course_id']);
        });

        // Tabel untuk progress detail per chapter/lesson
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('lesson_id'); // ID dari lesson dalam course
            $table->boolean('is_completed')->default(false);
            $table->integer('time_spent_seconds')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Satu enrollment hanya punya satu progress per lesson
            $table->unique(['enrollment_id', 'lesson_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
    }
};