<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->char('Movie_ID', 6)->primary();
            $table->string('Title', 150);
            $table->string('Original_Title', 150)->nullable();
            $table->integer('Release_Year');
            $table->decimal('Duration_Hours', 3, 1)->default(0.0);
            $table->integer('TMDB_ID')->nullable();
            $table->char('Genre_ID', 3)->nullable();
            $table->char('Submitted_By', 5);
            $table->string('Approval_Status', 20)->default('pending');
            $table->char('Approved_By', 5)->nullable();
            $table->string('Poster', 255)->nullable();
            $table->text('Description')->nullable();
            $table->softDeletes('DeletedAt');
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();

            $table->foreign('Genre_ID')->references('Genre_ID')->on('types')->nullOnDelete();
            $table->foreign('Submitted_By')->references('User_ID')->on('users')->cascadeOnDelete();
            $table->foreign('Approved_By')->references('Admin_ID')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
