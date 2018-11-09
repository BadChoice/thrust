<?php
/**
 * Config for PHP-CS-Fixer ver2
 * To add it to phptstorm:
 * https://laracasts.com/series/how-to-be-awesome-in-phpstorm/episodes/26
 * https://hackernoon.com/how-to-configure-phpstorm-to-use-php-cs-fixer-1844991e521f
 */
$rules = [
    '@PSR2' => true,
    // addtional rules
    'array_syntax' => ['syntax' => 'short'],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'blank_line_after_opening_tag' => true,
    'method_separation' => true,
    'no_useless_return' => true,
    'no_useless_else' => true,
    'ternary_to_null_coalescing' => true,
    'binary_operator_spaces' => ['align_double_arrow' => true, 'align_equals' => true],
    'single_quote' => true,
];
$excludes = [
    // add exclude project directory
    'vendor',
    'node_modules',
    'bootstrap',
    'storage'
];
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(false)
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in('.')
            ->exclude($excludes)
            ->notName('README.md')
            ->notName('_ide_helper.php')
            ->notName('*.xml')
            ->notName('*.yml')
    );

