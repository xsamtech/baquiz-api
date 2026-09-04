<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ApiController;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FlexPayPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_pending_mobile_money_payment_from_flexpay_response(): void
    {
        config([
            'services.flexpay.api_token' => 'test-token',
            'services.flexpay.merchant' => 'test-merchant',
            'services.flexpay.gateway_mobile' => 'https://flexpay.test/mobile',
        ]);

        $user = User::factory()->create();

        Http::fake([
            'https://flexpay.test/mobile' => Http::response([
                'code' => '0',
                'message' => 'Transaction sent successfully.',
                'orderNumber' => 'ORDER-123',
            ]),
        ]);

        $result = $this->paymentStarter()->start([
            'user_id' => $user->id,
            'type' => 1,
            'phone' => '243815894649',
            'amount' => 12.50,
            'currency' => 'USD',
            'description' => 'Clash participation',
        ]);

        $this->assertTrue($result['successful']);
        $this->assertSame(2, $result['payment']->status);
        $this->assertSame('ORDER-123', $result['payment']->order_number);
        $this->assertMatchesRegularExpression('/^REF-\d{8}-'.$user->id.'$/', $result['payment']->reference);
        $this->assertDatabaseHas((new Payment)->getTable(), [
            'id' => $result['payment']->id,
            'status' => 2,
            'order_number' => 'ORDER-123',
        ]);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://flexpay.test/mobile'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && $request['callbackUrl'] === url('/api/payments/flexpay/'.$request['reference'].'/callback')
                && ! isset($request['callback_url']);
        });
    }

    private function paymentStarter(): object
    {
        return new class extends ApiController
        {
            /**
             * @param  array<string, mixed>  $attributes
             * @return array{payment: Payment, response: array<string, mixed>, successful: bool}
             */
            public function start(array $attributes): array
            {
                return $this->initiateFlexPayPayment($attributes);
            }
        };
    }
}
