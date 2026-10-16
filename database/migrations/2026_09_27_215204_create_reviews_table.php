<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->char('Review_ID', 10)->primary();
            $table->char('User_ID', 5);
            $table->char('Movie_ID', 6);
            $table->text('Review_Text');
            $table->decimal('Score', 3, 1)->default(0.0);
            $table->tinyInteger('Spoiler')->default(0);
            $table->softDeletes('DeletedAt');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();

            $table->foreign('User_ID')->references('User_ID')->on('users')->cascadeOnDelete();
            $table->foreign('Movie_ID')->references('Movie_ID')->on('activities')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
