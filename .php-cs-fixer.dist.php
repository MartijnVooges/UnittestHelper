<?php

$finder = (new PhpCsFixer\Finder())
    ->in('src')
    ->in('tests')
    ->notPath([
        'ApiGroups.php', // De import logica bovenaan wordt niet herkend
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'ordered_imports' => [
            'imports_order' => ['const', 'class', 'function'],
        ],
        'php_unit_method_casing' => false,
        'phpdoc_summary' => false,
        'yoda_style' => false,
    ])
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder);
