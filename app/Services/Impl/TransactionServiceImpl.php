<?php

namespace App\Services\Impl;

use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionServiceImpl implements TransactionService
{
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
        return Transaction::create($data);
    }

    public function getBalance(): int
    {
        $income = Transaction::where('type', 'income')->sum('amount');
        $expense = Transaction::where('type', 'expense')->sum('amount');
        return $income - $expense;
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
