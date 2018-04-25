<?php
/**
 * Created on : 2018-04-23 20:24:25 Mon
 * Encoding   : UTF-8
 * Description: php-cs-fixer format config
 *
 * @author    @qii404 <qii404.me>
 */

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'braces' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'blank_line_after_namespace' => true,
        'blank_line_before_return' => true,
        'blank_line_after_opening_tag' => true,
        'class_attributes_separation' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_indent' => true,
        'phpdoc_align' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'combine_consecutive_unsets' => true,
        'declare_equal_normalize' => true,
        'elseif' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'method_argument_space' => true,
        'method_separation' => true,
        'new_with_braces' => true,
        'cast_spaces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_empty_statement' => true,
        'no_leading_namespace_whitespace' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_before_comma_in_array' => true,
        'phpdoc_scalar' => true,
        'single_blank_line_before_namespace' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'trailing_comma_in_multiline_array' => true,
        'array_syntax' => ['syntax' => 'short'],
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        'array_indentation' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true, 'align_equals' => true],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests/')
            ->exclude('vendor/')
            ->in(__DIR__)
    )
    ->setUsingCache(true)
    ->setCacheFile('.php_cs.cache')
;

return $config;
