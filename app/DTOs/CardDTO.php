<?php

namespace App\DTOs;

class CardDTO
{
    /** @var string */
    private $card_number;
    /** @var string */
    private $card_expiration_date;
    /** @var string */
    private $card_holder_name;
    /** @var string */
    private $card_cvv;

    public function __construct(array $params)
    {
        // TODO Validation
        $this->setCardNumber($params['card_number'] ?? '');
        $this->setCardExpirationDate($params['card_expiration_date'] ?? '');
        $this->setCardHolderName($params['card_holder_name'] ?? '');
        $this->setCardCvv($params['card_cvv'] ?? '');
    }

    public function getCardCvv()
    {
        return $this->card_cvv;
    }

    public function setCardCvv(string $card_cvv)
    {
        $this->card_cvv = $card_cvv;

        return $this;
    }

    public function getCardHolderName()
    {
        return $this->card_holder_name;
    }

    public function setCardHolderName(string $card_holder_name)
    {
        $this->card_holder_name = $card_holder_name;

        return $this;
    }

    public function getCardExpirationDate()
    {
        return $this->card_expiration_date;
    }

    public function setCardExpirationDate(string $card_expiration_date)
    {
        $this->card_expiration_date = $card_expiration_date;

        return $this;
    }

    public function getCardNumber()
    {
        return $this->card_number;
    }

    public function setCardNumber(string $card_number)
    {
        $this->card_number = $card_number;

        return $this;
    }
}
