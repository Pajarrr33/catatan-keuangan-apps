<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['Income', 'Expense']),
            'amount' => $this->faker->numberBetween(1000, 1000000),
        ];
    }
}