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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // ชื่อกระดาน เช่น "10 หนังซอมบี้ที่ดีที่สุด"
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('collections'); }
};
