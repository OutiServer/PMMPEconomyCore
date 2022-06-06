<?php

declare(strict_types=1);

namespace Ken_Cir\EconomyCore\Utils;

use Ken_Cir\EconomyCore\EconomyCore;
use Ken_Cir\EconomyCore\Tasks\BackFormTask;

class FormUtil
{
    static function backForm(callable $callable, array $args = [], int $timeout = 1): void
    {
        EconomyCore::getInstance()->getScheduler()->scheduleDelayedTask(new BackFormTask($callable, $args), $timeout * 20);
    }
}