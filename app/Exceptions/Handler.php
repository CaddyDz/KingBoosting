<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Capture errors and report to sentry
	 *
	 * Bind the sentry SDK to the app container
	 *
	 * @param Throwable $exception Exception
	 * @return void
	 **/
	public function report(Throwable $exception): void
	{
		if ($this->shouldReport($exception) && app()->bound('sentry')) {
			app('sentry')->captureException($exception);
		}

		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Throwable $exception
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Throwable
	 */
	public function render($request, Throwable $exception)
	{
		if ($exception instanceof NotFoundHttpException) {
			return response(['status' => 'Not Found'], 404);
		} elseif ($exception instanceof MethodNotAllowedHttpException) {
			return response(['status' => 'Bad Request'], 400);
		}
		return parent::render($request, $exception);
	}
}
