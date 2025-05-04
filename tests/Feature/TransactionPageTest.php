<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionPageTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplayTransactionPage(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeText('Catatan Keuangan Apps')
            ->assertSeeText('Daftar transaksi');
    }

    public function testFilterTransactionByType(): void
    {
        Transaction::factory()
            ->count(3)
            ->create(['type' => 'Income']);
        Transaction::factory()
            ->count(3)
            ->create(['type' => 'Expense']);

        $this->get('/?type=Income')
            ->assertSee('Pendapatan');
        $this->get('/?type=Expense')
            ->assertSee('Pengeluaran');
    }

    public function testSortTransactionByType(): void
    {
        Transaction::factory()->create([
            'date' => now()->subDays(2),
            'amount' => 100000,
            'type' => 'Income'
        ]);
        Transaction::factory()->create([
            'date' => now()->subDays(1),
            'amount' => 50000,
            'type' => 'Income'
        ]);
        Transaction::factory()->create([
            'date' => now(),
            'amount' => 200000,
            'type' => 'Income'
        ]);

        $this->get('/?sort=biggest')
            ->assertViewHas('transactions', function ($transactions) {
                return $transactions->first()->amount === 200000;
            });

        $this->get('/?sort=smallest')
            ->assertViewHas('transactions', function ($transactions) {
                return $transactions->first()->amount === 50000;
            });

        $this->get('/?sort=latest')
            ->assertViewHas('transactions', function ($transactions) {
                return Carbon::parse($transactions->first()->date)->isToday();
            });

        $this->get('/?sort=oldest')
            ->assertViewHas('transactions', function ($transactions) {
                return Carbon::parse($transactions->first()->date)
                             ->isSameDay(now()->subDays(2));
            });
    }

    public function testDisplayCorretBalanceCalculation(): void
    {
        Transaction::factory()->create([
            'date' => now()->subDays(2),
            'amount' => 100000,
            'type' => 'Income'
        ]);
        Transaction::factory()->create([
            'date' => now()->subDays(1),
            'amount' => 50000,
            'type' => 'Expense'
        ]);
        Transaction::factory()->create([
            'date' => now(),
            'amount' => 200000,
            'type' => 'Income'
        ]);

        $this->get('/')
            ->assertViewHas('balance', 250000)
            ->assertViewHas('income', 300000)
            ->assertViewHas('expense', 50000);
    }
}
