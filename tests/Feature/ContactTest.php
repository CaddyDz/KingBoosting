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
		$response->assertOk();
	}

	/**
	 * Message array.
	 *
	 * Returns a fake array of data for a contact message
	 *
	 **/
	private function message(): array
	{
		return [
			'name' => $this->faker->name,
			'email' => $this->faker->email,
			'subject' => $this->faker->realText(20),
			'message' => $this->faker->realText(50),
			'Hacker' => '1337', // Tests request data trimming function
		];
	}

	/**
	 * Test no internet error.
	 *
	 * Assert that an error message is displayed
	 * in case no internet connection is detected
	 *
	 **/
	public function test_no_internet_exception(): void
	{
		config(['mail.default' => 'smtp']);
		config(['mail.mailers.smtp.username' => null]);
		$response = $this->post('/api/contact', $this->message());
		$response->assertStatus(599);
	}
}
