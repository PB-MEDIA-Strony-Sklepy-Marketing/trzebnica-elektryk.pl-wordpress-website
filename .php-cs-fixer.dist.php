<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src/wp-content/themes/betheme',
        __DIR__ . '/src/wp-content/themes/betheme-child',
        __DIR__ . '/dist/wp-content/themes/hubag',
        __DIR__ . '/dist/wp-content/themes/hubag-child',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'single_quote' => true,
        'no_trailing_whitespace' => true,
        'no_extra_blank_lines' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => false,
    ])
    ->setFinder($finder);