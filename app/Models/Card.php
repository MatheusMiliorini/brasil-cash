<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    use HasFactory;

    protected $primaryKey = 'card_id';

    protected $table = 'cards';

    protected $fillable = [
        'card_number',
        'card_expiration_date',
        'card_holder_name',
        'card_cvv',
    ];
}
