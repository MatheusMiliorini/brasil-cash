<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDTO extends Model
{

    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'amount',
        'payment_method',
        'async',
        'capture',
        'installments',
        'card',
    ];
}
