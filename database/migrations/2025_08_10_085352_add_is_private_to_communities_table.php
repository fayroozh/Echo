<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->boolean('is_private')->default(false)->after('category_id');
        });
    }
    
    public function down()
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->dropColumn('is_private');
        });
    }
    
};
