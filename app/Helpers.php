<?php

declare(strict_types=1);

function sluggify(string $string): string
{
	// replace non letter or digits by -
	$url = preg_replace('~[^\pL\d]+~u', '-', $string);

	// transliterate
	try {
		$url = iconv('utf-8', 'us-ascii//TRANSLIT', $url);
	} catch (Exception $ex) {
		return 'n-a';
	}

	// remove unwanted characters
	$url = preg_replace('~[^-\w]+~', '', $url);

	// trim
	$url = trim($url, '-');

	// remove duplicate -
	$url = preg_replace('~-+~', '-', $url);

	// lowercase
	$url = strtolower($url);

	if (empty($url)) {
		return 'n-a';
	}

	return $url;
}
