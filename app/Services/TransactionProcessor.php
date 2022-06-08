<?php

namespace App\Services;

use App\Models\TransactionDTO;

class TransactionProcessor
{

    public function addToQueue(int $transactionId)
    {
        // TODO
    }

    public function process(int $transactionId): TransactionDTO
    {
        $transaction = TransactionDTO::find($transactionId);
        $card = $transaction->card;
        $lastCardDigit = substr($card->card_number, -1);
        if ($lastCardDigit < 5) {
            $transaction->status = TransactionDTO::AUTHORIZED;
            $transaction->paid_amount = $transaction->amount;
        } else if ($lastCardDigit < 9) {
            $transaction->status = TransactionDTO::REFUSED;
        } else {
            $transaction->status = TransactionDTO::STATUS[array_rand(TransactionDTO::STATUS)];
        }
        $transaction->save();
        return $transaction;
    }
}
