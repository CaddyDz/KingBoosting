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
		'blank_line_after_opening_tag' => true,
		'declare_strict_types' => true,
		'ordered_imports' => ['sort_algorithm' => 'length'],
		'no_unused_imports' => true,
	])->setIndent("\t")
	->setFinder($finder);
