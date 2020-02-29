<?php

namespace Tests\Unit;

use App\Order;
use Tests\TestCase;

class MetricsTest extends TestCase
{
    /**
     * Test MyOrders are counted
     *
     * @return void
     */
    public function testMyOrdersAreCounted(): void
    {
        $this->login('Super Admin');
        $this->createUserOrder();
        $response = $this->get('/nova-api/orders/metrics/my-orders');
        $responseBody = json_decode($response->getContent(), true);
        $value = $responseBody['value'];
        $count = $value['value'];
        $this->assertEquals(8, $count);
    }

    public function createUserOrder(): void
    {
        create(Order::class, [
            'client_id' => auth()->id(),
            'booster_id' => auth()->id()
        ], 'create', 8);
    }
}
