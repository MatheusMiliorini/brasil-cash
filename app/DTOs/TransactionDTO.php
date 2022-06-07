<?php

namespace App\DTOs;

use App\Exceptions\TransactionValidationException;

class TransactionDTO
{

    /** @var int */
    private $amount;
    /** @var string */
    private $payment_method;
    /** @var bool */
    private $async;
    /** @var bool */
    private $capture;
    /** @var int */
    private $installments;
    /** @var Card */
    private $card;

    public function __construct(array $params)
    {
        // TODO Validation
        $this->setAmount($params['amount'] ?? 0);
        $this->setPaymentMethod($params['payment_method'] ?? '');
        $this->setAsync($params['async'] ?? false);
        $this->setCapture($params['capture'] ?? false);
        $this->setInstallments($params['installments'] ?? 1);

        $card = new CardDTO($params['card']);
        $this->setCard($card);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(int $amount)
    {
        if (!$amount) {
            throw new TransactionValidationException("amount must be informed and be greater than zero!");
        }
        $this->amount = $amount;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method)
    {
        if (!$payment_method) {
            throw new TransactionValidationException("payment_method must be informed!");
        }
        $this->payment_method = $payment_method;
        return $this;
    }

    public function isAsync()
    {
        return $this->async;
    }

    public function setAsync(bool $async)
    {
        $this->async = $async;

        return $this;
    }

    public function isCapture()
    {
        return $this->capture;
    }

    public function setCapture(bool $capture)
    {
        $this->capture = $capture;

        return $this;
    }

    public function getInstallments()
    {
        return $this->installments;
    }

    public function setInstallments(int $installments)
    {
        $this->installments = $installments;

        return $this;
    }

    public function getCard()
    {
        return $this->card;
    }

    public function setCard(CardDTO $card)
    {
        $this->card = $card;

        return $this;
    }
}
