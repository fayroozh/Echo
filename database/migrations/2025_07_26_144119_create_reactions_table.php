<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'happy', 'wow', 'love', etc.
            $table->timestamps();
            
            // منع التفاعل المتكرر من نفس المستخدم
            $table->unique(['user_id', 'quote_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};