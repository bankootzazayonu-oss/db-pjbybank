<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            // เพิ่มคอลัมน์ชนิด JSON เพื่อเก็บ Array ชื่อ Tier (เช่น {"S": "โคตรเทพ", "A": "สนุกดี"})
            $table->json('tier_labels')->nullable();
        });
    }

    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('tier_labels');
        });
    }
};