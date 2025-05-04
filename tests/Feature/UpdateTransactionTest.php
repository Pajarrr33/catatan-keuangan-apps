<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateTransaction(): void
    {
        $transaction = Transaction::factory()->create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 5000000,
        ]);

        $data = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Updated Salary',
            'type' => 'Income',
            'amount' => 200000,
        ];

        $response = $this->put('/transactions/' . $transaction->id, $data);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('transactions', $data);
    }

    public function testValidateInsufficientBalanceWhenUpdateHigherExpense(): void
    {
        Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 500000,
        ]);

        $transaction = Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Initial Expense',
            'type' => 'Expense',
            'amount' => 100000,
        ]);

        $updatedData = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Updated Expense',
            'type' => 'Expense',
            'amount' => 600000,
        ];

        $response = $this->put('/transactions/' . $transaction->id, $updatedData);

        $response->assertSessionHasErrors(['amount']);

        $this->assertDatabaseMissing('transactions', $updatedData);
    }
}
