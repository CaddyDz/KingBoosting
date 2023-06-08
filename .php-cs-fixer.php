<?php

declare(strict_types=1);

use PhpCsFixer\{Config, Finder};

return (new Config())
	->setRules([
		'array_syntax' => [
			'syntax' => 'short',
		],
		'blank_line_after_namespace' => true,
		'blank_line_after_opening_tag' => true,
		'cast_spaces' => ['space' => 'single'],
		'declare_strict_types' => true,
		'group_import' => true,
		'indentation_type' => true,
		'no_blank_lines_after_class_opening' => true,
		'no_unused_imports' => true,
		'ordered_imports' => ['sort_algorithm' => 'length'],
		'ordered_traits' => true,
		'single_blank_line_before_namespace' => true,
		'single_line_after_imports' => true,
		'use_arrow_functions' => true,
	])->setIndent("\t")
	->setFinder(
		(new Finder())
			->in(__DIR__)
			->name('*.php')
			->notPath('bootstrap/cache')
			->notPath('node_modules')
			->notPath('nova')
			->notPath('storage')
			->notPath('vendor')
			->notName('*.blade.php')
			->notName('_ide_helper.php')
			->ignoreDotFiles(true)
			->ignoreVCS(true)
			->append([__FILE__])
	);
