<?php

namespace App\Repositories;

use App\Models\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transactionDTO): Transaction;
}
