<?php

namespace App\Nova;

use App\User as UserModel;
use Laravel\Nova\Http\Requests\NovaRequest;

class Member extends User
{
	/**
	 * The logical group associated with the resource.
	 *
	 * @var string
	 */
	public static $group = 'Admin';

	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\User';

	/**
	 * Build an "index" query for the given resource.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query)
	{
		$user_ids_with_member_role = UserModel::role('Member')->select('id')->pluck('id')->toArray();
		return $query->whereIn('id', $user_ids_with_member_role);
	}
}
