<?php

use Symfony\CS\AbstractFixer;
use Symfony\CS\DocBlock\DocBlock;
use Symfony\CS\Tokenizer\Tokens;

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader(<<<EOF
OAuth2 Client Bundle
Copyright (c) KnpUniversity <http://knpuniversity.com/>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF
);

return Symfony\CS\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        '-psr0',
        '-phpdoc_params',
        '-phpdoc_separation',
        '-phpdoc_var_without_name',
        '-return',
        'concat_with_spaces',
        'header_comment',
        'newline_after_open_tag',
        'short_array_syntax',
        'strict_param',
    ])
    ->finder(
        Symfony\CS\Finder::create()
            ->files()
            ->name('*.php')
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->exclude('tests/app/cache')
    )
    ;