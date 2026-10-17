<?php

declare(strict_types=1);

it('reports healthy at /up', function (): void {
	$this->get('/up')->assertOk();
});
