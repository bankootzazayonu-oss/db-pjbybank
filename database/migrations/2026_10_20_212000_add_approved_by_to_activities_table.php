<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('activities', 'Approved_By')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->char('Approved_By', 5)->nullable()->after('Approval_Status');
                $table->foreign('Approved_By')->references('Admin_ID')->on('admins')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('activities', 'Approved_By')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropForeign(['Approved_By']);
                $table->dropColumn('Approved_By');
            });
        }
    }
};
