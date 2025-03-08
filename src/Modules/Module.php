<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

interface Module
{
    public function getLabel(): string;

    public function run(): void;
}
