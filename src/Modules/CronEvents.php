<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

class CronEvents extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'Cron events';
    }

    public function getLabel(): string
    {
        return 'Disable cron event processing.';
    }

    public function testmode(): void
    {
        $this->disabled();
    }

    public function disabled(): void
    {
        if (wp_doing_cron()) {
            exit;
        }
    }
}
