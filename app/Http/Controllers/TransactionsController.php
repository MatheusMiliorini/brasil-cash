<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionValidationException;
use App\Models\TransactionDTO;
use App\Services\TransactionsService;
use App\Services\TransactionsValidator;
use Exception;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    /** @var TransactionsService */
    private $transactionsService;
    /** @var TransactionsValidator */
    private $transactionsValidator;

    public function __construct(
        TransactionsService $transactionsService,
        TransactionsValidator $transactionsValidator
    ) {
        $this->transactionsService = $transactionsService;
        $this->transactionsValidator = $transactionsValidator;
    }

    public function save(Request $request)
    {
        $data = $this->getSaveParamsWithDefaultValues($request->all());
        $transaction = new TransactionDTO($data);
        try {
            $this->transactionsValidator->validate($transaction);
        } catch (TransactionValidationException $exception) {
            return response()
                ->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            return response()
                ->json(['error' => "There was an error processing the request."])
                ->status(500);
        }
        $createdTransaction = $this->transactionsService->save($transaction);
        return response()->json($createdTransaction);
    }

    private function getSaveParamsWithDefaultValues(array $params): array
    {
        $defaults = [
            'async' => true,
            'capture' => true,
            'installments' => 1
        ];
        foreach ($defaults as $key => $value) {
            if (!isset($params[$key])) {
                $params[$key] = $value;
            }
        }
        return $params;
    }
}
