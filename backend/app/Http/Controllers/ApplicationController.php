<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Http\Resources\Json\{AnonymousResourceCollection, JsonResource};

class ApplicationController extends Controller
{
	/**
	 * A booster application from the storefront's /apply page.
	 */
	public function store(ApplicationRequest $request): JsonResponse
	{
		Application::query()->create($request->validated());

		return response()->json(['message' => 'Thanks! We read every application and reply by email.'], 201);
	}

	/**
	 * The admin's inbox: unread first, then newest.
	 */
	public function index(): AnonymousResourceCollection
	{
		$this->authorize('viewAny', Application::class);

		return JsonResource::collection(Application::query()->orderByRaw('reviewed_at is not null')->latest()->latest('id')->paginate(20));
	}

	public function review(Application $application): JsonResource
	{
		$this->authorize('review', $application);

		$application->forceFill(['reviewed_at' => $application->reviewed_at ?? now()])->save();

		return new JsonResource($application);
	}
}
