<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use App\Notifications\CustomNotification;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\{ActionFields, Text};

class NotifyAction extends Action
{
	use InteractsWithQueue, Queueable;

	/**
	 * Get the displayable name of the action.
	 *
	 * @return string
	 */
	public function name(): string
	{
		return __('Send custom notification to user');
	}

	/**
	 * Send custom notification to target user collection.
	 *
	 * @param \Laravel\Nova\Fields\ActionFields $fields
	 * @param \Illuminate\Support\Collection $users
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $users)
	{
		foreach ($users as $user) {
			$user->notify(new CustomNotification($fields->message));
		}
		return Action::message(__('Notification has been sent'));
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields(): array
	{
		return [
			Text::make(__('Message'), 'message')->required(),
		];
	}
}
