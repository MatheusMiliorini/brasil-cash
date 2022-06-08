<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionValidationException;
use App\Http\Resources\TransactionResource;
use App\Models\TransactionDTO;
use App\Services\TransactionsService;
use App\Services\TransactionsValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{

    /** @var TransactionsService */
    private $transactionsService;

    public function __construct(
        TransactionsService $transactionsService,
    ) {
        $this->transactionsService = $transactionsService;
    }

    public function save(Request $request)
    {
        $transaction = new TransactionDTO($request->all());
        try {
            $createdTransaction = $this->transactionsService->save($transaction);
            return response()
                ->json(new TransactionResource($createdTransaction));
        } catch (TransactionValidationException $exception) {
            return response()
                ->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()
                ->json(['error' => "There was an error processing the request."], 500);
        }
    }

    public function capture(Request $request, TransactionDTO $transaction)
    {
        try {
            $capturedTransaction = $this->transactionsService->capture($transaction, $request->amount ?? 0);
            return response()
                ->json(new TransactionResource($capturedTransaction));
        } catch (TransactionValidationException $exception) {
            return response()
                ->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            Log::error($exception);
            return response()
                ->json(['error' => "There was an error processing the request."], 500);
        }
    }
}
