<?php

namespace Tests\Feature;

use App\Tier;
use App\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use WithFaker;

    /**
     * Assert orders can be created
     *
     * @return void
     */
    public function testThatWeCanPlaceAnOrder()
    {
        $this->login('Member');
        $order = [
            'current_tier' => "Silver",
            'current_division' => "Division I",
            'server' => 'EU-West',
            'wins' => 4,
            'game_mode' => 'Solo/Duo',
            'options' => [
                'specific_champions' => true,
                'priority_order' => true,
                'with_streaming' => false,
                'appear_offline' => true,
            ],
            'in_game_name' => 'caddy',
            'comments' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates molestiae quidem totam doloribus sapiente dolores ducimus at? Iusto aut vitae inventore dolorem officiis laboriosam, iure reprehenderit ipsum rem, libero earum?',
            'spell_order' => 'D Flash',
            'discount_code' => null,
            'purchase' => "Nothing",
            'client_id' => rand(1, 600),
            'service_service_type_id' => rand(1, 19),
            'price' => $this->faker->randomFloat(2, 1, 99),
            'booster_id' => $this->faker->optional()->passthrough(rand(6, 100)),
        ];
        $response = $this->json('POST', '/api/orders', $order);
        $response->assertOk();
    }
}
