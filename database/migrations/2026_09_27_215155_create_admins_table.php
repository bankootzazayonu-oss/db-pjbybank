<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->char('Admin_ID', 5)->primary();
            $table->string('Username', 50);
            $table->string('Email', 100)->unique();
            $table->string('Password', 255);
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();
            $table->softDeletes('DeletedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
