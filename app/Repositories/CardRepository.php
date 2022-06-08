<?php

namespace App\Repositories;

use App\Models\Card;

interface CardRepository
{
    public function save(Card $card): Card;
}
