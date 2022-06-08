<?php

namespace App\Repositories;

use App\Models\CardDTO;

class CardRepositoryImpl implements CardRepository
{
    public function save(CardDTO $card): CardDTO
    {
        $card->save();
        return $card->refresh();
    }
}
