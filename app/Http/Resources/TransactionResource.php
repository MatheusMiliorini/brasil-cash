<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $card = [
            'card_id' => $this->card->card_id,
            'card_holder_name' => $this->card->card_holder_name,
            'card_first_digits' => substr($this->card->card_number, 0, 6),
            'card_last_digits' => substr($this->card->card_number, -4),
            'created_at' => $this->card->created_at,
            'updated_at' => $this->card->updated_at,
        ];
        $transaction = [
            'id' => $this->id,
            'installments' => $this->installments,
            'amount' => $this->amount,
            'captured_amount' => $this->captured_amount,
            'paid_amount' => $this->paid_amount,
            'payment_method' => $this->payment_method,
            'ref_id' => $this->ref_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'card' => $card,
        ];
        return $transaction;
    }
}
