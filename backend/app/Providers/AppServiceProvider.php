<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Policies\MetricsPolicy;
use Illuminate\Support\Facades\Gate;
use Stripe\StripeClient;
use App\Payments\{PaymentGateway, StripePaymentGateway, UnconfiguredPaymentGateway};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->app->bind(PaymentGateway::class, fn (): PaymentGateway => filled(config('services.stripe.secret'))
			? new StripePaymentGateway(new StripeClient([
				'api_key' => (string) config('services.stripe.secret'),
				'max_network_retries' => 2,
			]))
			: new UnconfiguredPaymentGateway);
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// The leaked-password check calls the Have I Been Pwned API, so it only runs in production.
		Password::defaults(fn (): Password => app()->isProduction() ? Password::min(8)->uncompromised() : Password::min(8));

		// The reset link opens the Nuxt app, which posts the new password to /api/reset-password.
		ResetPassword::createUrlUsing(fn (User $user, string $token): string => config('app.frontend_url').'/reset-password?'.http_build_query([
			'token' => $token,
			'email' => $user->email,
		]));

		// Abilities without a model still live in a policy class (docs/adr/0002)
		Gate::define('viewMetrics', [MetricsPolicy::class, 'view']);

		$this->configureRateLimiting();
	}

	private function configureRateLimiting(): void
	{
		RateLimiter::for('login', fn (Request $request): array => [
			Limit::perMinute(5)->by(Str::lower($request->string('email')->toString()).'|'.$request->ip()),
			Limit::perMinute(20)->by($request->ip()),
		]);

		RateLimiter::for('register', fn (Request $request): Limit => Limit::perHour(10)->by($request->ip()));

		RateLimiter::for('password-reset', fn (Request $request): Limit => Limit::perMinute(3)->by($request->ip()));

		RateLimiter::for('applications', fn (Request $request): Limit => Limit::perHour(3)->by($request->ip()));

		RateLimiter::for('checkout', fn (Request $request): Limit => Limit::perMinute(10)->by($request->user()?->getAuthIdentifier() ?? $request->ip()));

		RateLimiter::for('messages', fn (Request $request): Limit => Limit::perMinute(30)->by($request->user()?->getAuthIdentifier() ?? $request->ip()));

		// The order builder re-quotes as the customer changes options, debounced on the client
		RateLimiter::for('quotes', fn (Request $request): Limit => Limit::perMinute(120)->by($request->user()?->getAuthIdentifier() ?? $request->ip()));
	}
}
