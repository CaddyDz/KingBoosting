<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\{Fine, User};
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\FineResource;
use Illuminate\Http\{JsonResponse, Request, Response};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FineController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$this->authorize('viewAny', Fine::class);
		$validated = $request->validate(['booster_id' => ['sometimes', 'integer']]);

		return FineResource::collection(Fine::query()
			->with('booster')
			->when($validated['booster_id'] ?? null, fn ($query, int $booster) => $query->where('booster_id', $booster))
			->latest()
			->latest('id')
			->paginate(20));
	}

	public function store(Request $request): JsonResponse
	{
		$this->authorize('create', Fine::class);
		$validated = $request->validate([
			'booster_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'booster')],
			...$this->rules(),
		]);

		$fine = new Fine($validated);
		$fine->booster()->associate((int) $validated['booster_id']);
		/** @var User $admin */
		$admin = $request->user();
		$fine->issuer()->associate($admin);
		$fine->save();

		return (new FineResource($fine->load('booster')))->response()->setStatusCode(201);
	}

	public function update(Request $request, Fine $fine): FineResource
	{
		$this->authorize('update', $fine);

		$fine->update($request->validate($this->rules()));

		return new FineResource($fine->load('booster'));
	}

	public function destroy(Fine $fine): Response
	{
		$this->authorize('delete', $fine);

		$fine->delete();

		return response()->noContent();
	}

	/**
	 * @return array<string, list<string>>
	 */
	private function rules(): array
	{
		return [
			'amount_cents' => ['required', 'integer', 'min:1', 'max:100000'],
			'reason' => ['required', 'string', 'max:255'],
		];
	}
}
