<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('communities', function (Blueprint $table) {
            // First drop the existing foreign key
            $table->dropForeign(['category_id']);
            
            // Add the new foreign key pointing to the correct table
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('communities', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['category_id']);
            
            // Restore the original foreign key
            $table->foreign('category_id')
                  ->references('id')
                  ->on('community_categories')
                  ->onDelete('set null');
        });
    }
};