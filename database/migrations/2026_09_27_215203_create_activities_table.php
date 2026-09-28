<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->text('review')->nullable();
            $table->string('image')->nullable();
            $table->float('hours')->default(0);
            
            // เชื่อม FK ไปตาราง users และ types
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('types')->nullOnDelete();
            
            // ใช้เก็บสถานะ: 0 = รออนุมัติ, 1 = อนุมัติแล้ว
            $table->boolean('is_approved')->default(0); 
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('activities'); }
};
