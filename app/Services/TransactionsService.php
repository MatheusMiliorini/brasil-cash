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

    public function __construct(
        TransactionsValidator $transactionsValidator,
        CardRepository $cardRepository,
        TransactionRepository $transactionRepository,
    ) {
        $this->transactionsValidator = $transactionsValidator;
        $this->cardRepository = $cardRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function save(TransactionDTO $transactionDto)
    {
        $this->transactionsValidator->validate($transactionDto);
        DB::beginTransaction();
        $card = $this->cardRepository->save(new CardDTO($transactionDto->card));
        $transactionDto->prepareFieldsForInsert($card);
        $transaction = $this->transactionRepository->save($transactionDto);
        DB::commit();
        $transaction->card = $card;
        
        return $transaction;
    }
}
