<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_reviews', function (Blueprint $table) {
            $table->char('User_ID', 5);
            $table->char('Review_ID', 10);
            $table->dateTime('Saved_At')->useCurrent();

            $table->primary(['User_ID', 'Review_ID']);
            $table->foreign('User_ID')->references('User_ID')->on('users')->cascadeOnDelete();
            $table->foreign('Review_ID')->references('Review_ID')->on('reviews')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_reviews');
    }
};
