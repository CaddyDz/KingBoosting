<?php

$finder = PhpCsFixer\Finder::create()
	->exclude('/vendor')
	->exclude('/nova')
	->exclude('node_modules')
	->in(__DIR__);

return PhpCsFixer\Config::create()
	->setRules([
		'array_syntax' => ['syntax' => 'short'],
		'indentation_type' => true,
	])->setIndent("\t")
	->setFinder($finder);
