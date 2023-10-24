<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Size; 

// SizeFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Size;

class SizeFactory extends Factory
{
    protected $model = Size::class;

    public function definition()
    {
        return [
            'customer_id' => $this->faker->numberBetween(1, 10), 
            'size_name' => $this->faker->word, 
            'collar_size' => $this->faker->randomFloat(2, 14, 18), 
            'chest_size' => $this->faker->randomFloat(2, 32, 48), 
            'sleeve_length' => $this->faker->numberBetween(25, 38), 
            'cuff_size' => $this->faker->randomFloat(2, 7, 10), 
            'shoulder_size' => $this->faker->randomFloat(2, 16, 22),
            'waist_size' => $this->faker->randomFloat(2, 28, 40), 
            'shirt_length' => $this->faker->numberBetween(25, 34), 
            'legs_length' => $this->faker->numberBetween(28, 36), 
            'description' => $this->faker->sentence, 
            'category' => $this->faker->randomElement(['shirt_pant', 'kurta', 'blazer', 'kameez_shalwar']), 
        ];
    }
}
