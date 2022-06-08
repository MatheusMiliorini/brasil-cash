<?php

namespace App\Services;

use App\Exceptions\TransactionValidationException;
use App\Models\Card;
use App\Models\Transaction;

class TransactionsValidator
{

    public function validateForSave(Transaction $transaction): bool
    {
        $this->validateTransaction($transaction);
        $this->validateCard(new Card($transaction->card ?? []));
        return true;
    }

    public function validateForCapture(Transaction $transaction, int $amount): bool
    {
        if ($amount < 100) {
            throw new TransactionValidationException('amount must be greater or equal to 100.');
        }
        if ($amount > $transaction->amount) {
            throw new TransactionValidationException("amount can't be greater than $transaction->amount.");
        }
        if ($transaction->status !== Transaction::AUTHORIZED) {
            throw new TransactionValidationException('Only authorized transactions can be captured.');
        }
        return true;
    }

    private function validateTransaction(Transaction $transaction)
    {
        if (!$transaction->amount || $transaction->amount < 100) {
            throw new TransactionValidationException("amount must be informed and be greater than 100.");
        }
        if (!$transaction->payment_method) {
            throw new TransactionValidationException("payment_method must be informed.");
        }
        if ($transaction->payment_method !== "credit_card") {
            throw new TransactionValidationException("payment_method can only be credit_card.");
        }
        if ($transaction->installments < 1 || $transaction->installments > 12) {
            throw new TransactionValidationException("installments must be between 1 and 12.");
        }
    }

    private function validateCard(Card $card)
    {
        $fieldsForValidation = [
            'card_number',
            'card_expiration_date',
            'card_holder_name',
            'card_cvv',
        ];
        foreach ($fieldsForValidation as $field) {
            if (!isset($card->$field) || !$card->$field) {
                throw new TransactionValidationException("$field in card must be informed.");
            }
        }
    }
}
