<?php

namespace App\Http\Controllers;

use App\DTOs\TransactionDTO;
use App\Exceptions\TransactionValidationException;
use App\Services\TransactionsService;
use Exception;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    /** @var TransactionsService */
    private $transactionsService;

    public function __construct(TransactionsService $transactionsService) {
        $this->transactionsService = $transactionsService;
    }

    public function save(Request $request)
    {
        try {
            $transaction = new TransactionDTO($request->all());
        } catch (TransactionValidationException $exception) {
            return response()
                ->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            return response()
                ->json(['error' => "There was an error processing the request."])
                ->status(500);
        }
        return response()->json();
    }
}
