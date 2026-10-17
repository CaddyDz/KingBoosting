<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Request};
use App\Http\Resources\UserResource;

class MeController extends Controller
{
	/**
	 * Always 200: a resource for a user created in this process would otherwise answer 201.
	 */
	public function __invoke(Request $request): JsonResponse
	{
		return (new UserResource($request->user()))->response()->setStatusCode(200);
	}
}
