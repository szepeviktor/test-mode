<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use SzepeViktor\TestMode\AdminPage;
use SzepeViktor\TestMode\ModuleLoader;
use SzepeViktor\TestMode\ModuleMode;

use function __;
use function get_option;
use function sanitize_title;

abstract class BaseModule
{
    public function getSlug(): string
    {
        return sanitize_title($this->getName());
    }

    public function getName(): string
    {
        return substr(self::class, strlen(ModuleLoader::MODULE_NAMESPACE));
    }

    public function getLabel(): string
    {
        return sprintf(
            /* translators: %s: Module name. */
            __('Put %s in test mode or disable it.', 'szv-test-mode'),
            $this->getName()
        );
    }

    public function activate(): void
    {
        $mode = get_option(AdminPage::OPTION_PREFIX.$this->getSlug());

        switch ($mode) {
            case ModuleMode::TEST_MODE:
                $this->testmode();
                break;
            case ModuleMode::DISABLED:
                $this->disabled();
                break;
        }
    }

    abstract public function testmode(): void;

    abstract public function disabled(): void;
}
