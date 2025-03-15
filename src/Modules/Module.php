<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

interface Module
{
    public function getName(): string;

    public function getLabel(): string;

    public function run(): void;
}
