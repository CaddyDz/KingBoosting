<?php

declare(strict_types=1);

Route::get('/debug-sentry', function () {
	throw new Exception('My first Sentry error!');
});
