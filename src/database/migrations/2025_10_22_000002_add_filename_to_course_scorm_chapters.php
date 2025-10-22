<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('course_scorm_chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('course_scorm_chapters', 'filename')) {
                $table->string('filename')->nullable()->after('title'); // Original ZIP filename
            }
        });
    }

    public function down()
    {
        Schema::table('course_scorm_chapters', function (Blueprint $table) {
            if (Schema::hasColumn('course_scorm_chapters', 'filename')) {
                $table->dropColumn('filename');
            }
        });
    }
};
