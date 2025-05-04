<?php

namespace App\Services;

interface TransactionService
{
    public function getAll(array $filters = [], int $limit = 10);
    public function create(array $data);
    public function update(array $data, int $id);
    public function destroy(int $id);
    public function getBalance();
    public function getIncome();
    public function getExpense();
}