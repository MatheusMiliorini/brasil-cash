<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{

    use HasFactory;

    const AUTHORIZED = 'authorized';
    const PAID = 'paid';
    const REFUSED = 'refused';
    const STATUS = [self::AUTHORIZED, self::PAID, self::REFUSED];

    protected $table = 'transactions';

    protected $fillable = [
        'amount',
        'payment_method',
        'async',
        'capture',
        'installments',
        'card',
    ];

    protected $attributes = [
        'async' => true,
        'capture' => true,
        'installments' => 1
    ];

    public function prepareFieldsForInsert(Card $cardDTO): void
    {
        if ($this->capture) {
            $this->captured_amount = $this->amount;
            $this->status = 'processing';
        } else {
            $this->status = 'authorized';
        }
        $this->ref_id = (string) Str::uuid();
        $this->card_id = $cardDTO->card_id;
        unset($this->card);
    }

    public function card()
    {
        return $this->hasOne(Card::class, 'card_id');
    }
}
