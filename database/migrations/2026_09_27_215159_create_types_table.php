<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types', function (Blueprint $table) {
            $table->char('Genre_ID', 3)->primary();
            $table->string('Genre_Name', 50);
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();
            $table->softDeletes('DeletedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
