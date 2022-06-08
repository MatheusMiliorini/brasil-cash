<?php

namespace App\Repositories;

use App\Models\TransactionDTO;

class TransactionRepositoryImpl implements TransactionRepository
{
    public function save(TransactionDTO $transactionDTO): TransactionDTO
    {
        $transactionDTO->save();
        return $transactionDTO->refresh();
    }
}
