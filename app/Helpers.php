<?php

function sluggify($string)
{
	$url = trim($string);
	$url = strtolower($url);
	$url = preg_replace('|[^a-z-A-Z0-9 _]|iu', '', $url);
	$url = preg_replace('/\s+/', ' ', $url);
	$url = str_replace(' ', '-', $url);
	return $url;
}
