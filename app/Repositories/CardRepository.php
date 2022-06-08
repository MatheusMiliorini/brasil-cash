<?php

namespace App\Repositories;

use App\Models\CardDTO;

interface CardRepository
{
    public function save(CardDTO $card): CardDTO;
}
