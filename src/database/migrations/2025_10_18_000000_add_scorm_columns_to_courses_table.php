<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add SCORM-related columns
            if (!Schema::hasColumn('courses', 'content_type')) {
                $table->string('content_type')->default('video')->nullable(); // video, scorm, text, etc
            }
            if (!Schema::hasColumn('courses', 'scorm_version')) {
                $table->string('scorm_version')->nullable(); // 1.2, 2004, etc
            }
            if (!Schema::hasColumn('courses', 'scorm_package_path')) {
                $table->string('scorm_package_path')->nullable(); // path to extracted SCORM files
            }
            if (!Schema::hasColumn('courses', 'scorm_manifest')) {
                $table->json('scorm_manifest')->nullable(); // parsed imsmanifest.xml
            }
            if (!Schema::hasColumn('courses', 'scorm_entry_point')) {
                $table->string('scorm_entry_point')->nullable(); // entry point for SCORM player
            }
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'content_type',
                'scorm_version',
                'scorm_package_path',
                'scorm_manifest',
                'scorm_entry_point',
            ]);
        });
    }
};
