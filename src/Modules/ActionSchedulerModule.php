<?php

declare(strict_types=1);

namespace SzepeViktor\TestMode\Modules;

use ActionScheduler;

use function add_action;
use function add_filter;
use function class_exists;
use function remove_action;

class ActionSchedulerModule extends BaseModule implements Module
{
    public function getName(): string
    {
        return 'Action Scheduler';
    }

    public function getLabel(): string
    {
        return 'Disable Action Scheduler queue processing.';
    }

    public function testmode(): void
    {
        $this->disabled();
    }

    public function disabled(): void
    {
        add_action(
            'init',
            static function (): void {
                if (!class_exists(ActionScheduler::class)) {
                    return;
                }

                remove_action(
                    'action_scheduler_run_queue',
                    [ActionScheduler::runner(), 'run']
                );
            },
            PHP_INT_MAX,
            0
        );

        add_filter(
            'action_scheduler_allow_async_request_runner',
            '__return_false',
            10,
            0
        );
    }
}
