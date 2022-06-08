<?php

namespace Tests\Unit;

use App\Exceptions\TransactionValidationException;
use App\Models\Card;
use App\Models\Transaction;
use App\Services\TransactionsValidator;
use Tests\TestCase;

class TransactionValidatorTest extends TestCase
{

    /** @var TransactionsValidator */
    private $transactionValidator;

    public function setUp(): void
    {
        parent::setUp();
        $this->transactionValidator = new TransactionsValidator();;
    }

    public function testValidationOk()
    {
        $transaction = Transaction::factory()->make();
        $transaction->card = Card::factory()->make()->toArray();
        $valid = $this->transactionValidator->validateForSave($transaction);
        $this->assertTrue($valid);
    }

    public function testValidationErrorWithNoCard()
    {
        $transaction = Transaction::factory()->make();
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);
    }

    public function testValidationWithInvalidCardFields()
    {
        $transaction = Transaction::factory()->make();
        $card = Card::factory()->make();
        $card->card_cvv = null;
        $transaction->card = $card->toArray();
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);
    }

    public function testValidationWithInvalidTransactionFields()
    {
        $transaction = Transaction::factory()->make();
        $transaction->amount = 50;
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);

        $transaction->amount = 1000;
        $transaction->payment_method = 'cash';
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);

        $transaction->payment_method = null;
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);

        $transaction->payment_method = 'credit_card';
        $transaction->installments = 50;
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForSave($transaction);
        }, TransactionValidationException::class);
    }

    public function testForCaptureOk()
    {
        $transaction = Transaction::factory()->make(['status' => Transaction::AUTHORIZED]);
        $valid = $this->transactionValidator->validateForCapture($transaction, $transaction->amount);
        $this->assertTrue($valid);
    }

    public function testForCaptureWithInvalidFields()
    {
        $transaction = Transaction::factory()->make(['payment_method' => 'other']);
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForCapture($transaction, $transaction->amount + 1);
        }, TransactionValidationException::class);

        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForCapture($transaction, 50);
        }, TransactionValidationException::class);

        $transaction->status = Transaction::PAID;
        $this->assertThrows(function () use ($transaction) {
            $this->transactionValidator->validateForCapture($transaction, $transaction->amount);
        }, TransactionValidationException::class);
    }
}
