<?php

declare(strict_types=1);

namespace outiserver\economycore\Utils;

use outiserver\economycore\EconomyCore;
use outiserver\economycore\Tasks\BackFormTask;

class FormUtil
{
    static function backForm(callable $callable, array $args = [], int $timeout = 1): void
    {
        EconomyCore::getInstance()->getScheduler()->scheduleDelayedTask(new BackFormTask($callable, $args), $timeout * 20);
    }
}