<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'phone' => '12345678901',
            'password' => bcrypt('password'),
        ]);
    }
}
