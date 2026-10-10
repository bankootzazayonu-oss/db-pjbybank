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
        Schema::dropIfExists('activity_actor');
        
        if (Schema::hasColumn('activities', 'director_id')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropForeign(['director_id']);
                $table->dropColumn('director_id');
            });
        }
        
        Schema::dropIfExists('actors');
        Schema::dropIfExists('directors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not providing down() logic as this is a destructive feature removal
    }
};
