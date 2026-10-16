<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->char('Tier_ID', 10)->primary();
            $table->char('User_ID', 5);
            $table->string('Tier_Title', 100);
            $table->json('Tier_Labels')->nullable();
            $table->softDeletes('DeletedAt');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();

            $table->foreign('User_ID')->references('User_ID')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
