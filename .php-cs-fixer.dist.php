<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ );

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2.0' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
