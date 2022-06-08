<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepositoryImpl implements TransactionRepository
{
    public function save(Transaction $transactionDTO): Transaction
    {
        $transactionDTO->save();
        return $transactionDTO->refresh();
    }
}
