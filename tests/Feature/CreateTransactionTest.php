<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateIncomeTransaction(): void
    {
        $data = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 5000000,
        ];

        $response = $this->post('/transactions', $data);
        
        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('transactions', $data);
    }

    public function testCreateExpenseTransaction(): void
    {
        Transaction::create([
            'date' => now()->format('Y-m-d'),
            'description' => 'Salary',
            'type' => 'Income',
            'amount' => 5000000,
        ]);

        $data = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Groceries',
            'type' => 'Expense',
            'amount' => 200000,
        ];

        $response = $this->post('/transactions', $data);
        
        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('transactions', $data);
    }

    public function testValidateInsufficientBalanceForExpense(): void
    {
        $data = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Expensive Item',
            'type' => 'Expense',
            'amount' => 1000000,
        ];

        $response = $this->post('/transactions', $data);

        $response->assertSessionHasErrors(['amount']);

        $this->assertDatabaseMissing('transactions', $data);
    }

    public function testValidateTransactionCreateFields(): void
    {
        $invalidData = [
            'date' => 'invalid-date',
            'description' => '',
            'type' => 'InvalidType',
            'amount' => -100,
        ];

        $this->post('/transactions', $invalidData)
            ->assertSessionHasErrors([
                'date',
                'description',
                'type',
                'amount',
            ]);
    }

    public function testValidateFutureDates(): void
    {
        $invalidData = [
            'date' => now()->addDays(2)->format('Y-m-d'),
            'description' => 'Futere Transaction',
            'type' => 'Income',
            'amount' => 100000,
        ];

        $this->post('/transactions', $invalidData)
            ->assertSessionHasErrors(['date']);
    }
}
