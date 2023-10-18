<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ContactTest extends TestCase
{
	use WithFaker;

	/**
	 * Test sending a contact email.
	 *
	 * @return void test results
	 */
	public function test_api_emails_can_be_sent(): void
	{
		$response = $this->post('/api/contact', $this->message());
		$response->assertCreated();
	}

	/**
	 * Message array.
	 *
	 * Returns a fake array of data for a contact message.
	 *
	 * @return array
	 */
	private function message(): array
	{
		return [
			'category' => $this->faker->randomElement([
				'General Question',
				'Order Issue',
				'Bug Report',
				'Other',
			]),
			'name' => $this->faker->name,
			'email' => $this->faker->email,
			'subject' => $this->faker->realText(20),
			'message' => $this->faker->realText(50),
			'Hacker' => '1337', // Tests request data trimming function
		];
	}

	/**
	 * Assert exception thrown
	 *
	 * Assert that an error message is displayed
	 * in case of an exception being thrown
	 * when sending an email.
	 *
	 * @return void
	 */
	public function test_exception(): void
	{
		$this->expectExceptionCode(530);
		config(['mail.default' => 'smtp']);
		config(['mail.mailers.smtp.username' => null]);
		$this->post('/api/contact', $this->message());
	}
}
