<?php

namespace Tests;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getTransaction(array $transactionFields = [], array $cardFields = []): Transaction
    {
        $transaction = Transaction::factory()->make($transactionFields);
        $card = Card::factory()->make($cardFields);
        $transaction->card = $card->toArray();
        return $transaction;
    }
}
