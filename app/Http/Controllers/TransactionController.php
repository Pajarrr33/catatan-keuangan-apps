<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['type','sort']);
        $transactions = $this->transactionService->getAll($filters);
        $balance = $this->transactionService->getBalance();
        $income = $this->transactionService->getIncome();
        $expense = $this->transactionService->getExpense();

        // dd($transaction->toArray(), $balance, $income, $expense);
        return view('main', compact('transactions', 'balance', 'income', 'expense'));
    }

    public function store(TransactionRequest $request)
    {
        $data = $request->validated();

        $this->transactionService->create($data);

        return redirect()->back();
    }
}
