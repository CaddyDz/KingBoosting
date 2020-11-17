<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Order;
use App\Service;
use DatabaseSeeder;
use Tests\TestCase;
use App\ServiceKind;
use App\ServiceType;
use TiersTableSeeder;
use Spatie\Permission\Models\Role;

class MetricsTest extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();
		app(DatabaseSeeder::class)->call(TiersTableSeeder::class);
	}
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
		// Create a service type factory
		$service_type = create(ServiceType::class);
		$service = create(Service::class, ['kind_id' => $kind->id]);
		$service->types()->attach($service_type);
		// Attach the newly created service to the service type
		create(Order::class, [
			'client_id' => auth()->id(),
			'booster_id' => auth()->id(),
			'service_service_type_id' => $service->id,
		], 'create', 8);
	}
}
