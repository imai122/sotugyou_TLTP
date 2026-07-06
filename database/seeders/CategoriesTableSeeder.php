<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_id' => 1, 'category_name' => '家電製品'],
            ['category_id' => 2, 'category_name' => '衣類'],
            ['category_id' => 3, 'category_name' => '書籍類'],
            ['category_id' => 4, 'category_name' => 'スポーツ用品'],
            ['category_id' => 5, 'category_name' => '美容関連'],
            ['category_id' => 6, 'category_name' => 'その他'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        //
    }
}
