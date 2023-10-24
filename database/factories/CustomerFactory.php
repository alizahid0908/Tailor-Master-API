<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            // Define your attributes here
            'name' => $this->faker->firstName,
            'phone' => $this->faker->numberBetween(1000000000, 9999999999),
            'user_id' => $this->faker->numberBetween(1, 10), 

        ];
    }
}
