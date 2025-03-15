<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Support;

use SzepeViktor\TestMode\ModuleLoader;

use __;

trait Autolabel
{
    public function getLabel(): string
    {
        return sprintf(__('Put %s in test mode', 'szv-test-mode'), $this->getName());
    }
}
