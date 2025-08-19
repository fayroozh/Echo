<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // hex color
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default categories
        DB::table('categories')->insert([
            [
                'name' => 'أدب',
                'slug' => 'literature',
                'description' => 'مجتمعات الأدب والكتابة',
                'color' => '#3B82F6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شعر',
                'slug' => 'poetry',
                'description' => 'مجتمعات الشعر والقصائد',
                'color' => '#8B5CF6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'علم',
                'slug' => 'science',
                'description' => 'مجتمعات العلوم والتكنولوجيا',
                'color' => '#10B981',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'فلسفة',
                'slug' => 'philosophy',
                'description' => 'مجتمعات الفلسفة والفكر',
                'color' => '#F59E0B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'تاريخ',
                'slug' => 'history',
                'description' => 'مجتمعات التاريخ والحضارة',
                'color' => '#EF4444',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
