<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactMessageReceived;

function contactMessage(array $overrides = []): array
{
	return [
		'category' => 'order',
		'name' => 'Riley',
		'email' => 'riley@kingboosting.test',
		'message' => 'My booster has not replied since yesterday.',
		...$overrides,
	];
}

it('sends contact messages to every admin, in the app and by email', function (): void {
	Notification::fake();
	$admins = User::factory()->admin()->count(2)->create();
	$moderator = User::factory()->moderator()->create();

	$this->postJson('/api/contact', contactMessage())->assertCreated()->assertJsonStructure(['message']);

	Notification::assertSentTo($admins, ContactMessageReceived::class, function (ContactMessageReceived $notification, array $channels, User $admin): bool {
		$mail = $notification->toMail($admin);

		return $channels === ['database', 'broadcast', 'mail']
			&& $mail->replyTo === [['riley@kingboosting.test', 'Riley']]
			&& $mail->subject === 'Contact form: Order issue'
			&& $notification->toArray($admin)['body'] === 'Riley (riley@kingboosting.test): My booster has not replied since yesterday.';
	});
	Notification::assertNotSentTo($moderator, ContactMessageReceived::class);
});

it('validates contact messages', function (): void {
	$this->postJson('/api/contact', ['category' => 'application', 'email' => 'nope', 'message' => 'Hi'])
		->assertUnprocessable()
		->assertJsonValidationErrors(['category', 'name', 'email', 'message']);
});

it('limits how often one visitor can write', function (): void {
	Notification::fake();
	User::factory()->admin()->create();

	foreach (range(1, 5) as $attempt) {
		$this->postJson('/api/contact', contactMessage())->assertCreated();
	}

	$this->postJson('/api/contact', contactMessage())->assertTooManyRequests();
});
