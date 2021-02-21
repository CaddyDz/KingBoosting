<?php

declare(strict_types=1);

function create(string $model, array $properties = [], string $method = 'create', int $times = null)
{
	return $model::factory($times)->$method($properties);
}
