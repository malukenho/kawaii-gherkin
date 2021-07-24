<?php

declare(strict_types=1);

use PedroTroller\CS\Fixer\Fixers;
use PedroTroller\CS\Fixer\RuleSetFactory;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(
        RuleSetFactory::create()
            ->phpCsFixer(true)
            ->php(7.4, true)
            ->pedrotroller(true)
            ->enable('align_multiline_comment')
            ->enable('array_indentation')
            ->enable('binary_operator_spaces', ['operators' => ['=' => 'align_single_space_minimal', '=>' => 'align_single_space_minimal']])
            ->enable('class_attributes_separation', ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']])
            ->enable('header_comment', ['header' => ''])
            ->enable('no_superfluous_phpdoc_tags')
            ->enable('ordered_imports')
            ->enable('ordered_interfaces')
            ->enable('simplified_null_return')
            ->enable('static_lambda')
            ->disable('no_superfluous_phpdoc_tags')
            ->disable('phpdoc_to_comment')
            ->disable('simplified_null_return')
            ->getRules()
    )
    ->setUsingCache(false)
    ->registerCustomFixers(new Fixers())
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->append([__DIR__.'/bin/kawaii', __FILE__])
    )
;
