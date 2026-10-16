<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_platform', function (Blueprint $table) {
            $table->char('Movie_ID', 6);
            $table->char('Platform_ID', 4);

            $table->primary(['Movie_ID', 'Platform_ID']);
            $table->foreign('Movie_ID')->references('Movie_ID')->on('activities')->cascadeOnDelete();
            $table->foreign('Platform_ID')->references('Platform_ID')->on('platforms')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_platform');
    }
};
