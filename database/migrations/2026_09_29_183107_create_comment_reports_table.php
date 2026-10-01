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
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            // เก็บ ID ของรีวิวที่ถูกรีพอร์ต (ถ้าคอมเมนต์โดนลบ รีพอร์ตจะโดนลบตาม)
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            // เก็บ ID ของคนที่กดรีพอร์ต
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // เหตุผลที่รีพอร์ต
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
    }
};
