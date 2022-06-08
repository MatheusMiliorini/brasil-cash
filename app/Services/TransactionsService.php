<?php

namespace App\Services;

use App\Exceptions\TransactionValidationException;
use App\Models\Card;
use App\Models\Transaction;
use App\Repositories\CardRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class responsible for CRUD operations with Transactions
 */
class TransactionsService
{
    /** @var TransactionsValidator */
    private $transactionsValidator;
    /** @var CardRepository */
    private $cardRepository;
    /** @var TransactionRepository */
    private $transactionRepository;
    /** @var TransactionProcessor */
    private $transactionProcessor;

    public function __construct(
        TransactionsValidator $transactionsValidator,
        CardRepository $cardRepository,
        TransactionRepository $transactionRepository,
        TransactionProcessor $transactionProcessor
    ) {
        $this->transactionsValidator = $transactionsValidator;
        $this->cardRepository = $cardRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionProcessor = $transactionProcessor;
    }

    public function save(Transaction $transactionDto): Transaction
    {
        $this->transactionsValidator->validateForSave($transactionDto);
        DB::beginTransaction();
        $card = $this->cardRepository->save(new Card($transactionDto->card));
        $transactionDto->prepareFieldsForInsert($card);
        $transaction = $this->transactionRepository->save($transactionDto);
        DB::commit();
        $transaction->card = $card;
        if ($transaction->capture) {
            if ($transaction->async) {
                $this->transactionProcessor->addToQueue($transaction->id);
            } else {
                $transaction = $this->transactionProcessor->process($transaction->id);
                sleep(1); // Just to give a feeling of processing ;)
            }
        }
        return $transaction;
    }

    public function capture(Transaction $transaction, int $amount): Transaction
    {
        $this->transactionsValidator->validateForCapture($transaction, $amount);
        $transaction->captured_amount = $amount;
        $transaction->paid_amount = $amount;
        $transaction->status = Transaction::PAID;
        $transaction->save();
        return $transaction;
    }
}
