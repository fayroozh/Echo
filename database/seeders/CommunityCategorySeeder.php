<?php

namespace Database\Seeders;

use App\Models\CommunityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommunityCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'الأدب والشعر', 'icon' => 'book'],
            ['name' => 'الفن والإبداع', 'icon' => 'paint-brush'],
            ['name' => 'التنمية الذاتية', 'icon' => 'user-graduate'],
            ['name' => 'الثقافة العامة', 'icon' => 'globe'],
            ['name' => 'الدين والروحانيات', 'icon' => 'pray'],
            ['name' => 'العلوم والمعرفة', 'icon' => 'microscope'],
            ['name' => 'الفلسفة والفكر', 'icon' => 'brain'],
            ['name' => 'القراءة والكتب', 'icon' => 'books'],
        ];

        foreach ($categories as $category) {
            CommunityCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon']
            ]);
        }
    }
}