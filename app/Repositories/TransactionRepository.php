<?php

namespace App\Repositories;

use App\Models\TransactionDTO;

interface TransactionRepository
{
    public function save(TransactionDTO $transactionDTO): TransactionDTO;
}
