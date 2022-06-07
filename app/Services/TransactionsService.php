<?php

namespace App\Services;

use App\Models\CardDTO;
use App\Models\TransactionDTO;
use Illuminate\Support\Facades\DB;

/**
 * Class responsible for CRUD operations with Transactions
 */
class TransactionsService
{

    public function save(TransactionDTO $transactionDto)
    {
        DB::beginTransaction();
        $cardDto = new CardDTO($transactionDto->card);
        $cardDto->save();
        // $transactionDto['card_id'] = $card->card_id;
        // $transactionDto->save();
        DB::commit();
        return $transactionDto;
    }
}
