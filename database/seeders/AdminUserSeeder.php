<?php

namespace Database\Seeders;

use App\Models\YIC_user;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        YIC_user::create([
            'user_id' => 'admin01',
            'name' => 'システム管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('P@ssw0rd'),
            'role' => 1,
            
            'postal_code' => '000-0000',
            'address' => '東京都',
            'phone_number' => '000-0000-0000',
            'bank_account' => '1234567',
            'listing_count' => 0,
            'purchase_count' => 0,
            'rating' => 0,
        ]);

        YIC_user::create([
        'user_id' => 'shop_admin01',
        'name' => 'ショップ管理者',
        'email' => 'shop@example.com',
        'password' => Hash::make('p@ssw0rd'),
        'role' => 2,

        'postal_code' => '000-0000',
        'address' => '大阪府大阪市',
        'phone_number' => '080-0000-0000',
        'bank_account' => '2222222',
        'listing_count' => 0,
        'purchase_count' => 0,
        'rating' => 0,
    ]);
    }
}