<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepositoryImpl implements CardRepository
{
    public function save(Card $card): Card
    {
        $card->save();
        return $card->refresh();
    }
}
