<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->char('Report_ID', 10)->primary();
            $table->char('Review_ID', 10);
            $table->char('User_ID', 5);
            $table->string('Reason', 255);
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();
            $table->softDeletes('DeletedAt');

            $table->foreign('Review_ID')->references('Review_ID')->on('reviews')->cascadeOnDelete();
            $table->foreign('User_ID')->references('User_ID')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
