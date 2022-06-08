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
        $possibleStatus = ['paid', 'refused', 'authorized'];
        if ($lastCardDigit < 5) {
            $transaction->status = $possibleStatus[0];
            $transaction->paid_amount = $transaction->amount;
        } else if ($lastCardDigit < 9) {
            $transaction->status = $possibleStatus[1];
        } else {
            $transaction->status = $possibleStatus[array_rand($possibleStatus)];
        }
        $transaction->save();
        return $transaction;
    }
}
