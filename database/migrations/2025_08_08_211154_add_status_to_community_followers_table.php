<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('community_followers', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('community_followers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
