<?php

namespace App\Services;

interface TransactionService
{
    public function getAll(array $filters = [], int $limit = 10);
    public function create(array $data);
    public function getBalance();
    public function getIncome();
    public function getExpense();
}