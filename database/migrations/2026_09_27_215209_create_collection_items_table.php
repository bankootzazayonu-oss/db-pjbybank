<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_items', function (Blueprint $table) {
            $table->char('Tier_ID', 10);
            $table->char('Movie_ID', 6);
            $table->char('Tier_Level', 2)->default('B');

            $table->primary(['Tier_ID', 'Movie_ID']);
            $table->foreign('Tier_ID')->references('Tier_ID')->on('collections')->cascadeOnDelete();
            $table->foreign('Movie_ID')->references('Movie_ID')->on('activities')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_items');
    }
};
