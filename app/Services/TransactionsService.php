<?php

namespace App\Services;

use App\Models\CardDTO;
use App\Models\TransactionDTO;
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

    public function save(TransactionDTO $transactionDto): TransactionDTO
    {
        $this->transactionsValidator->validate($transactionDto);
        DB::beginTransaction();
        $card = $this->cardRepository->save(new CardDTO($transactionDto->card));
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
}
