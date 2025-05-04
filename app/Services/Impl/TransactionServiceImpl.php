<?php

namespace App\Services\Impl;

use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionServiceImpl implements TransactionService
{
    private function validateSufficientBalance(float $amount): void
    {
        if ($this->getBalance() < $amount) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => 'Saldo tidak mencukupi'
            ]);
        }
    }
    public function getAll(array $filters = [], int $limit = 10)
    {
        $query = Transaction::query();

        // Filter by transaction type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
    
        // Sorting logic
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'biggest':
                    $query->orderBy('amount', 'desc');
                    break;
                case 'smallest':
                    $query->orderBy('amount', 'asc');
                    break;
                case 'latest':
                    $query->orderBy('date', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('date', 'asc');
                    break;
                default:
                    $query->orderBy('date', 'desc');
            }
        } else {
            $query->orderBy('date', 'desc');
        }
    
        return $query->paginate($limit);
    }

    public function create(array $data)
    {
        if ($data['type'] !== 'Income') {
            $this->validateSufficientBalance($data['amount']);
        }
        return Transaction::create($data);
    }

    public function update(array $data, int $id)
    {
        if ($data['type'] !== 'Income') {
            $this->validateSufficientBalance($data['amount']);
        }
        return Transaction::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        $data = Transaction::where('id', $id)->first();
        if ($data->type === 'Income') {
            $this->validateSufficientBalance($data->amount);
        }
        return Transaction::where('id', $id)->delete();
    }

    public function getBalance(): int
    {
        $transaction = Transaction::selectRaw('
            SUM(CASE WHEN type = "Income" THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = "Expense" THEN amount ELSE 0 END) as expense 
        ')->first();
        return $transaction->income - $transaction->expense;
    }

    public function getIncome(): int
    {
        return Transaction::where('type', 'income')->sum('amount');
    }

    public function getExpense(): int
    {
        return Transaction::where('type', 'expense')->sum('amount');
    }
}
