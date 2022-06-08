<?php

namespace Tests\Unit;

use App\Exceptions\TransactionValidationException;
use App\Jobs\ProcessTransaction;
use App\Models\Card;
use App\Models\Transaction;
use App\Repositories\CardRepositoryImpl;
use App\Repositories\TransactionRepositoryImpl;
use App\Services\TransactionProcessor;
use App\Services\TransactionsService;
use App\Services\TransactionsValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{

    use RefreshDatabase;

    /** @var TransactionsService */
    private $transactionsService;

    public function setUp(): void
    {
        parent::setUp();
        $this->transactionsService = new TransactionsService(
            new TransactionsValidator(),
            new CardRepositoryImpl(),
            new TransactionRepositoryImpl(),
            new TransactionProcessor()
        );
    }

    public function testSaveOkAsync()
    {
        Queue::fake();

        $transaction = $this->getTransaction([
            'capture' => true,
            'async' => true
        ]);
        $newTransaction = $this->transactionsService->save($transaction);
        $this->assertTrue($newTransaction->id !== null);
        $this->assertTrue($newTransaction->card->card_id !== null);
        $this->assertEquals($newTransaction->captured_amount, $transaction->amount);
        $this->assertEquals($newTransaction->status, 'processing', 'Async must be processed later');

        Queue::assertPushed(ProcessTransaction::class);
    }

    public function testStatusByCardEndind()
    {
        $transaction = $this->getTransaction([
            'capture' => true,
            'async' => false,
        ], ['card_number' => '0000000000004']);
        $newTransaction = $this->transactionsService->save($transaction);
        $this->assertEquals($newTransaction->status, Transaction::PAID, 'Should have been processed');

        $transaction = $this->getTransaction([
            'capture' => true,
            'async' => false,
        ], ['card_number' => '0000000000005']);
        $newTransaction = $this->transactionsService->save($transaction);
        $this->assertEquals($newTransaction->status, Transaction::REFUSED, 'Should have been processed');

        $transaction = $this->getTransaction([
            'capture' => true,
            'async' => false,
        ], ['card_number' => '0000000000009']);
        $newTransaction = $this->transactionsService->save($transaction);
        $this->assertNotEquals($newTransaction->status, 'processing', 'Should have been processed, but with random status');
    }

    public function testShouldNotBeQueued()
    {
        Queue::fake();

        $transaction = $this->getTransaction([
            'capture' => true,
            'async' => false,
        ]);

        $this->transactionsService->save($transaction);
        Queue::assertNotPushed(ProcessTransaction::class);
    }

    public function testCapture()
    {
        $transaction = $this->getTransaction(['capture' => false]);
        $newTransaction = $this->transactionsService->save($transaction);
        unset($newTransaction->card);
        $captured = $this->transactionsService->capture($newTransaction, $transaction->amount);
        $this->assertEquals($captured->status, Transaction::PAID);
        $this->assertEquals($captured->captured_amount, $transaction->amount);
        $this->assertEquals($captured->paid_amount, $transaction->amount);

        $transaction = $this->getTransaction(['capture' => false]);
        $newTransaction = $this->transactionsService->save($transaction);
        unset($newTransaction->card);
        $this->assertThrows(function () use ($newTransaction, $transaction) {
            $this->transactionsService->capture($newTransaction, $transaction->amount + 50);
        }, TransactionValidationException::class);
    }
}
