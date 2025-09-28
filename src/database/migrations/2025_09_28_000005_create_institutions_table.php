<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Kode unik institusi
            $table->string('type'); // university, college, school, etc.
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique(); // Kode departemen
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_id')->nullable(); // NIM/No. Induk
            $table->string('status')->default('active'); // active, graduated, inactive
            $table->json('metadata')->nullable(); // Data tambahan seperti tahun masuk, dll
            $table->timestamps();

            // Satu user hanya bisa terdaftar sekali di institusi yang sama
            $table->unique(['user_id', 'institution_id']);
        });

        // Tabel untuk menyimpan course yang tersedia di institusi
        Schema::create('institution_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_mandatory')->default(false);
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->timestamps();

            // Satu course hanya bisa ditambahkan sekali ke institusi
            $table->unique(['institution_id', 'course_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('institution_courses');
        Schema::dropIfExists('student_institutions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('institutions');
    }
};