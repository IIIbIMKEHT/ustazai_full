<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            User::create([
                'name' => 'admin',
                'email' => 'kazitech2023@gmail.com',
                'role_id' => 1,
                'count' => 1000,
                'password' => bcrypt('admin123')
            ]);
        }
    }
}
