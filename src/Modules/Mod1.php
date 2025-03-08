<?php

namespace SzepeViktor\TestMode\Modules;

class Mod1 implements Module
{
    public function getLabel(): string
    {
        return 'Build a sandbox';
    }

    public function run(): void
    {
        echo 'SB';
    }
}
