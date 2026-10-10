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
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete(); 
            $table->enum('tier_rank', ['S', 'A', 'B', 'C', 'D'])->default('C'); 
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('collection_items'); }
};
