<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // Generate dummy size records using the factory
        Size::factory()->count(10)->create();
    }

}
