<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transactions')->insert([
            [
                'date' => '2023-05-03',
                'description' => 'Pembelian beras',
                'type' => 'expense',
                'amount' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date' => '2023-05-03',
                'description' => 'Gaji',
                'type' => 'Income',
                'amount' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
