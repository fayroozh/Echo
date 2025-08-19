<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->dropForeign(['from_user_id']);
            $table->dropColumn('from_user_id');
        });
    }
};
