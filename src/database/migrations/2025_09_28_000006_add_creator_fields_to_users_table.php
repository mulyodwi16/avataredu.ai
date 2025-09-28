<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'creator', 'admin'])->default('user')->after('email');
            $table->string('bio')->nullable()->after('avatar');
            $table->string('expertise')->nullable()->after('bio');
            $table->string('website')->nullable()->after('expertise');
            $table->json('social_links')->nullable()->after('website');
            $table->decimal('creator_rating', 3, 2)->nullable()->after('social_links');
            $table->integer('courses_count')->default(0)->after('creator_rating');
            $table->integer('students_count')->default(0)->after('courses_count');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'bio',
                'expertise',
                'website',
                'social_links',
                'creator_rating',
                'courses_count',
                'students_count'
            ]);
        });
    }
};