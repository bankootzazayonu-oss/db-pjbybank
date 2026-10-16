<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->char('Platform_ID', 4)->primary();
            $table->string('Platform_Name', 50);
            $table->string('Logo', 255)->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();
            $table->softDeletes('DeletedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
