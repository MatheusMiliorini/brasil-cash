<?php

namespace App\Services;

use App\Jobs\ProcessTransaction;
use App\Models\Transaction;

class TransactionProcessor
{

    public function addToQueue(int $transactionId)
    {
        ProcessTransaction::dispatch($transactionId);
    }

    public function process(int $transactionId): Transaction
    {
        $transaction = Transaction::find($transactionId);
        $card = $transaction->card;
        $lastCardDigit = substr($card->card_number, -1);
        if ($lastCardDigit < 5) {
            $transaction->status = Transaction::AUTHORIZED;
            $transaction->paid_amount = $transaction->amount;
        } else if ($lastCardDigit < 9) {
            $transaction->status = Transaction::REFUSED;
        } else {
            $transaction->status = Transaction::STATUS[array_rand(Transaction::STATUS)];
        }
        $transaction->save();
        return $transaction;
    }
}
