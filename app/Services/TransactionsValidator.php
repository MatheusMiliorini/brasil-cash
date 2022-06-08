<?php

namespace App\Services;

use App\Exceptions\TransactionValidationException;
use App\Models\CardDTO;
use App\Models\TransactionDTO;

class TransactionsValidator
{

    public function validate(TransactionDTO $transaction): bool
    {
        $this->validateTransaction($transaction);
        $this->validateCard(new CardDTO($transaction->card));
        return true;
    }

    private function validateTransaction(TransactionDTO $transaction)
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

    private function validateCard(CardDTO $card)
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
