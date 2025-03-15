<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use function add_filter;

class BlogPublic extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'Disallow robots';
    }

    public function getLabel(): string
    {
        return 'Disallow search engine robots in disabled.';
    }

    public function testmode(): void
    {
        // no op
    }

    public function disabled(): void
    {
        add_filter(
            'pre_option_'.'blog_public',
            '__return_zero',
            10,
            0
        );
    }
}
