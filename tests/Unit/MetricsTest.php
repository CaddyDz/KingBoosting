<?php

namespace Tests\Unit;

use App\Order;
use App\Service;
use App\ServiceKind;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

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
        Role::create(['name' => 'Booster']);
        $kind = ServiceKind::create(['name' => 'ELO BOOST']);
        $service = create(Service::class, ['kind_id' => $kind->id]);
        create(Order::class, [
            'client_id' => auth()->id(),
            'booster_id' => auth()->id(),
            'service_id' => $service->id,
        ], 'create', 8);
    }
}
