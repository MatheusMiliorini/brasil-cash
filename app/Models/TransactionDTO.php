<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $attributes = [
        'async' => true,
        'capture' => true,
        'installments' => 1
    ];

    public function prepareFieldsForInsert(CardDTO $cardDTO): void
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

    public function card() {
        return $this->hasOne(CardDTO::class, 'card_id');
    }
}
