<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('type', 10)->default('image')->after('id');   // image | video
            $table->string('video_url')->nullable()->after('image');
            $table->unsignedInteger('sort_order')->default(0)->after('video_url');
            $table->string('image')->nullable()->change();               // video tidak punya file gambar
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['type', 'video_url', 'sort_order']);
        });
    }
};
