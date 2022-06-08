<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionEndpointsTest extends TestCase
{

    use RefreshDatabase;

    public function testSaveEndpoint()
    {
        $transactionBody = $this->getTransaction();
        $response = $this->post('/transactions', $transactionBody->toArray());
        $response->assertStatus(200);
        $response->assertJsonStructure($this->getDesiredStructure());
    }

    public function testSaveEndpointShouldBe400()
    {
        $transactionBody = $this->getTransaction(['amount' => 10]);
        $response = $this->post('/transactions', $transactionBody->toArray());
        $response->assertStatus(400);
        $this->assertTrue($response['error'] !== null);
    }

    public function testCaptureEndpoint()
    {
        $transactionBody = $this->getTransaction(['capture' => false]);
        $response = $this->post('/transactions', $transactionBody->toArray());
        $response->assertStatus(200);

        $response = $this->post("/transactions/{$response['id']}/capture", ['amount' => $transactionBody->amount]);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->getDesiredStructure());
        $this->assertEquals($response['status'], Transaction::PAID);
    }

    public function testInvalidCapture()
    {
        $transactionBody = $this->getTransaction(['capture' => true]);
        $response = $this->post('/transactions', $transactionBody->toArray());
        $response->assertStatus(200);

        $response = $this->post("/transactions/{$response['id']}/capture", ['amount' => $transactionBody->amount]);
        $response->assertStatus(400, "Capture was true, can't capture again");
    }

    private function getDesiredStructure()
    {
        return [
            'id',
            'installments',
            'amount',
            'captured_amount',
            'paid_amount',
            'payment_method',
            'ref_id',
            'status',
            'created_at',
            'updated_at',
            'card' => [
                'card_id',
                'card_holder_name',
                'card_first_digits',
                'card_last_digits',
                'created_at',
                'updated_at',
            ]
        ];
    }
}
