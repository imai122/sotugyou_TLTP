<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\YIC_user;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = YIC_user::where('user_id', 'seller01')->first();

        if ($user) {
            Product::create([
                'seller_id'    => $user->user_id, 
                'category_id'  => 1,
                'product_name' => 'aaa',
                'comment'      => 'seeder',
                'wish_price'   => 5000,
                'end_date' => '2026-10-13 12:00:00',
                'status'       => '出品中'
            ]);
            Product::create([
                'seller_id'    => $user->user_id, 
                'category_id'  => 2,
                'product_name' => 'sss',
                'comment'      => 'Model',
                'wish_price'   => 500,
                'end_date' => '2026-10-14 12:00:00',
                'status'       => '出品中'
            ]);
            Product::create([
                'seller_id'    => $user->user_id, 
                'category_id'  => 3,
                'product_name' => 'zzz',
                'comment'      => 'Laravel',
                'wish_price'   => 2500,
                'end_date' => '2026-10-15 12:00:00',
                'status'       => '出品中'
            ]);


    }
}
}
