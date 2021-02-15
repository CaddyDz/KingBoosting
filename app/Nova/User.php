<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use App\Nova\Actions\NotifyAction;
use Laravel\Nova\Fields\{BelongsToMany, ID, MorphToMany, Password, Text};
use Vyuldashev\NovaPermission\{Permission, PermissionBooleanGroup, Role, RoleSelect};

class User extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\User::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'username';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'username', 'email',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		return [
			ID::make()->sortable(),

			BelongsToMany::make(__('Games'), 'games', Game::class)->canSee(fn ($request) => !$request->user()->hasRole('Member')),

			BelongsToMany::make(__('Servers'), 'servers', Server::class)->canSee(fn ($request) => !$request->user()->hasRole('Member')),

			Text::make('Username')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}')
				->readonly(fn ($req) => !$req->user()->hasRole('Admin')),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', 'string', 'min:8')
				->updateRules('nullable', 'string', 'min:8'),

			MorphToMany::make('Roles', 'roles', Role::class)->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			MorphToMany::make('Permissions', 'permissions', Permission::class)->canSee(fn ($request) => $request->user()->hasRole('Admin')),

			PermissionBooleanGroup::make('Permissions')->canSee(fn ($request) => $request->user()->hasRole('Admin')),

			RoleSelect::make('Role', 'roles')->canSee(fn ($request) => $request->user()->hasRole('Admin')),
		];
	}

	/**
	 * The icon of the resource.
	 *
	 * @return string
	 */
	public static function icon(): string
	{
		return view('nova::svg.icon-profile')->render();
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function cards(Request $request)
	{
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function filters(Request $request)
	{
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function lenses(Request $request)
	{
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function actions(Request $request): array
	{
		return [
			(new NotifyAction())
				->confirmButtonText(__('Send'))
				->cancelButtonText(__('Cancel'))
				->canSee(fn ($request) => $request->user()->hasRole('Admin'))->showOnTableRow(),
		];
	}
}
