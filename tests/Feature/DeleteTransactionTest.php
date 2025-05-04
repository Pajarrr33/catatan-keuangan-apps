<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteTransaction(): void
    {
        $transaction = Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 5000000,
        ]);

        $response = $this->delete('/transactions/' . $transaction->id);

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('transactions', [
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 5000000,
        ]);
    }

    public function testValidateInsufficientBalanceWhenDeleteTransaction(): void
    {
        $income = Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 500000,
        ]);

        Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Expense',
            'type' => 'Expense',
            'amount' => 400000,
        ]);

        $response = $this->delete('/transactions/' . $income->id);

        $response->assertSessionHasErrors(['amount']);

        $this->assertDatabaseHas('transactions', [
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 500000,
        ]);
    }
}
